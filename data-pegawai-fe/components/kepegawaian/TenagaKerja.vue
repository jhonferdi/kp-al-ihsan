<template>
	<div>
		<div class="d-sm-inline-flex mb-3">
			<div class="d-sm-inline-flex">
				<b-input-group class="mb-2 mb-sm-0 d-flex" style="max-width:230px">
					<b-form-input ref="search" @keyup.enter="updateSearch()" :value="ctx.filter"
						placeholder="Cari Tenaga Kerja"></b-form-input>
					<b-input-group-append>
						<span>
							<b-button variant="darkgreen" @click="updateSearch()">
								<b-icon icon="search" />
							</b-button>
						</span>
					</b-input-group-append>
				</b-input-group>
			</div>
			<div class="mx-3 d-none d-sm-block border-left" style="border-color:#E0E0E0 !important"></div>
			<!-- <b-button variant="darkgreen" :to="{ name: 'tenaga-kerja.create' }" size="sm"
		  class="text-nowrap mr-2 px-3 d-inline-flex align-items-center">Tambah Tenaga Kerja</b-button> -->
			<b-button variant="darkgreen" @click="openModalTambah()" size="sm"
				class="text-nowrap mr-2 px-3 d-inline-flex align-items-center">Tambah Tenaga Kerja</b-button>
			<ModalTenagaKerja :open="modalTenagaKerjaOpen" :tenagaKerja="tenagaKerjaData" size="xs"
				:title="`${title} Tenaga Kerja`" @close="modalTenagaKerjaOpen = false" @onSave="refreshData" />
		</div>
		<b-table class="table-rounded" striped hover responsive head-variant="darkgreen" id="tenaga-kerja-table"
			ref="table" :items="itemsProvider" :current-page="ctx.currentPage" :per-page="ctx.perPage"
			:filter="ctx.filter" :busy="isBusy" :sortBy="ctx.sortBy" :sortDesc="ctx.sortDesc" :fields="fields"
			show-empty :empty-text="'Tidak ada tenaga kerja ditemukan'"
			:empty-filtered-text="`Tidak ada tenaga kerja bernama '${ctx.filter}'`">
			<template #cell(index)="data">
				{{ data.index + ctx.perPage * (ctx.currentPage - 1) + 1 }}
			</template>
			<template #cell(tenaga_kerja_nama)="row">
				<span>{{ row.item.tenaga_kerja_nama || '-' }}</span>
			</template>
			<template #cell(actions)="row">
				<div class="text-nowrap">
					<!-- <nuxt-link :to="{ name: 'tenaga-kerja.edit', params: { id: row.item.tenaga_kerja_id }}"
			  class="text-darkblue p-1 d-inline-flex align-items-center bg-softblue rounded">
			  <i class="mb-0 bx bx-edit-alt" style="font-size:18px"></i>
			</nuxt-link> -->
					<a href="#" @click.prevent="openModalEdit(row.item)"
						class="text-darkblue p-1 d-inline-flex align-items-center bg-softblue rounded">
						<i class="mb-0 bx bx-edit-alt" style="font-size:18px"></i>
					</a>
					<!-- <b-link size="sm" class="text-danger p-1 d-inline-flex align-items-center bg-pink-new rounded" @click="promptDelete(row.item)">
			        <i class="mb-0 bx bx-trash" style="font-size:18px"></i>
			        </b-link> -->
					<b-link size="sm" class="text-danger p-1 d-inline-flex align-items-center bg-pink-new rounded"
						@click="onDeleteItem = row.item; modalHapusOpen = true">
						<i class="mb-0 bx bx-trash" style="font-size:18px"></i>
					</b-link>
				</div>
			</template>
		</b-table>
		<modal-hapus name="tenaga kerja" :open="modalHapusOpen" @close="modalHapusOpen = false" @delete="deleteItem" />
		<TablePagination v-model="ctx.currentPage" :itemsLength="pegawai_ruangan_count" @perPage="getPerPage" />
	</div>
</template>
<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { buildQueryParams } from '~/plugins/utils'
import ModalTenagaKerja from '../modals/ModalTenagaKerja.vue'

export default {
	middleware: ["auth"],
	data: () => ({
		ctx: {},
		tenaga_kerja: null,
		tenaga_kerja_count: null,
		ruangOptions: [],
		fields: [
			{ key: "index", label: "No", thClass: 'bg-darkgreen text-white' },
			{ key: 'tenaga_kerja_nama', label: 'Nama Tenaga Kerja', tdClass: 'w-100', sortable: true, sortDirection: 'asc', thClass: 'bg-darkgreen text-white' },
			{ key: "actions", label: "Aksi", thClass: 'bg-darkgreen text-white'}
		],
		isTableInit: false,
		isBusy: false,
		modalTenagaKerjaOpen: false,
		modalHapusOpen: false,
		onDeleteItem: null,
		tenagaKerjaData: null,
        title: null,
	}),
	mounted: function () {
		this.init();
	},
	watch: {
	},
	// computed: {
	//   selectedItems: function(){
	//     return this.pegawai.filter(function(ctx){
	//     })
	//   }
	// },
	methods: {
		async init() {
			if (window.tablectxtenagakerja) {
				this.ctx = window.tablectxtenagakerja
			} else {
				this.ctx = {
					currentPage: 1,
					perPage: 20,
					filter: '',
					sortBy: 'tenaga_kerja_nama',
					sortDesc: false
				}
			}

			let f1resp = (await axios.get('/tenaga-kerja?' + buildQueryParams(ctx))).data
			this.tenaga_kerja = f1resp.data
			this.tenaga_kerja_count = f1resp.count

		},
		async itemsProvider(ctx) {
			if (!this.isTableInit) {
				this.isTableInit = true
				return this.tenaga_kerja
			}

			ctx.params = this.ctx.params
			this.isBusy = true

			try {
				window.tablectxtenagakerja = ctx
				const response = await axios.get('/tenaga-kerja?' + buildQueryParams(ctx))
				this.isBusy = false
				this.tenaga_kerja_count = response.data.count
				return response.data.data
			} catch (error) {
				this.isBusy = false
				return []
			}
		},
		async promptDelete(item) {
			Swal.fire({
				title: 'Apakah Anda yakin hendak menghapus ' + item.tenaga_kerja_nama + '?',
				showDenyButton: true,
				confirmButtonText: `Hapus`,
				denyButtonText: `Batal`,
			}).then(async (result) => {
				/* Read more about isConfirmed, isDenied below */
				if (result.isConfirmed) {
					const response = await axios.delete(`/tenaga-kerja/${item.tenaga_kerja_id}`)
					Swal.fire({
						icon: 'success',
						title: 'Berhasil',
						text: 'Data berhasil dihapus',
						confirmButtonText: 'Ok',
					})
					this.$refs.table.refresh()
				}
			})
		},
		async deleteItem() {
			if (this.onDeleteItem) {
				const response = await axios.delete(`/tenaga-kerja/${this.onDeleteItem.tenaga_kerja_id}`)
				this.modalHapusOpen = false;
				Swal.fire({
					icon: "success",
					title: "Berhasil",
					text: "Data berhasil dihapus",
					confirmButtonText: "Ok",
				});
				this.$refs.table.refresh();
			}
		},
		refreshTable() {
			this.$refs.table.refresh();
		},
		getPerPage(count) {
			this.ctx.perPage = count;
		},
		updateSearch() {
			this.ctx.filter = this.$refs.search.$el.value;
			this.ctx.currentPage = 1;
		},
		showHapusModal(item) {
			this.onDeleteItem = item;
			this.modalHapusOpen = true;
		},
		openModalEdit(tenagaKerjaData) {
            this.modalTenagaKerjaOpen = true
            this.tenagaKerjaData = tenagaKerjaData
            this.title = 'Ubah'
        },
        openModalTambah() {
            this.modalTenagaKerjaOpen = true
            this.tenagaKerjaData = null
            this.title = 'Tambah'
        },
        async refreshData() {
            this.refreshTable()
        },
	},
	components: { ModalTenagaKerja }
}
</script>
