<template>
	<div>
		<div class="d-sm-inline-flex mb-3">
			<div class="d-sm-inline-flex">
				<b-input-group class="mb-2 mb-sm-0 d-flex" style="max-width:230px">
					<b-form-input ref="search" @keyup.enter="updateSearch()" :value="ctx.filter"
						placeholder="Cari Sub Spesialis"></b-form-input>
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
			<b-button variant="darkgreen" @click="openModalTambah()" size="sm"
				class="text-nowrap mr-2 px-3 d-inline-flex align-items-center">Tambah Sub Spesialis</b-button>
			<ModalSubSpesialis :open="modalSubSpesialisOpen" :sub_spesialis="subSpesialisData" size="xs"
				:title="`${title} Sub Spesialis`" @close="modalSubSpesialisOpen = false" @onSave="refreshData" />
		</div>
		<b-table class="table-rounded" striped hover responsive head-variant="darkgreen" id="tenaga-kerja-table"
			ref="table" :items="itemsProvider" :current-page="ctx.currentPage" :per-page="ctx.perPage"
			:filter="ctx.filter" :busy="isBusy" :sortBy="ctx.sortBy" :sortDesc="ctx.sortDesc" :fields="fields"
			show-empty :empty-text="'Tidak ada sub spesialis ditemukan'"
			:empty-filtered-text="`Tidak ada sub spesialis bernama '${ctx.filter}'`">
			<template #cell(index)="data">
				{{ data.index + ctx.perPage * (ctx.currentPage - 1) + 1 }}
			</template>
			<template #cell(sub_spesialis_nama)="row">
				<span>{{ row.item.sub_spesialis_nama || '-' }}</span>
			</template>
			<template #cell(actions)="row">
				<div class="text-nowrap">
					<a href="#" @click.prevent="openModalEdit(row.item)"
						class="text-darkblue p-1 d-inline-flex align-items-center bg-softblue rounded">
						<i class="mb-0 bx bx-edit-alt" style="font-size:18px"></i>
					</a>
					<b-link size="sm" class="text-danger p-1 d-inline-flex align-items-center bg-pink-new rounded"
						@click="showHapusModal(row.item)">
						<i class="mb-0 bx bx-trash" style="font-size:18px"></i>
					</b-link>
				</div>
			</template>
		</b-table>
		<modal-hapus name="jenis sub spesialis" :open="modalHapusOpen" @close="modalHapusOpen = false"
			@delete="deleteItem" />
		<TablePagination v-model="ctx.currentPage" :itemsLength="sub_spesialis_count" @perPage="getPerPage" />
	</div>
</template>
<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { buildQueryParams } from '~/plugins/utils'
import ModalSubSpesialis from '../modals/ModalSubSpesialis.vue'

export default {
	middleware: ["auth"],
	data: () => ({
		ctx: {},
		sub_spesialis: null,
		sub_spesialis_count: null,
		fields: [
			{ key: "index", label: "No", thClass: 'bg-darkgreen text-white' },
			// { key: 'ss', label: 'Sub Spesialis', sortable: false, sortDirection: 'asc' },
			{ key: 'sub_spesialis_nama', tdClass: 'w-100', label: 'Nama Sub Spesialis', sortable: true, sortDirection: 'asc', thClass: 'bg-darkgreen text-white' },
			{ key: "actions", label: "Aksi", thClass: 'bg-darkgreen text-white' }
		],
		isTableInit: false,
		isBusy: false,
		modalSubSpesialisOpen: false,
		modalHapusOpen: false,
		onDeleteItem: null
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
			if (window.tablectxsubspes) {
				this.ctx = window.tablectxsubspes
			} else {
				this.ctx = {
					currentPage: 1,
					perPage: 20,
					filter: '',
					sortBy: 'sub_spesialis_nama',
					sortDesc: false
				}
			}

			let f1resp = (await axios.get('/sub-spesialis?' + buildQueryParams(ctx))).data
			this.sub_spesialis = f1resp.data
			this.sub_spesialis_count = f1resp.count

		},
		async itemsProvider(ctx) {
			if (!this.isTableInit) {
				this.isTableInit = true
				return this.sub_spesialis
			}

			ctx.params = this.ctx.params
			this.isBusy = true

			try {
				window.tablectxsubspes = ctx
				const response = await axios.get('/sub-spesialis?' + buildQueryParams(ctx))
				this.isBusy = false
				this.sub_spesialis_count = response.data.count
				return response.data.data
			} catch (error) {
				this.isBusy = false
				return []
			}
		},
		async promptDelete(item) {
			Swal.fire({
				title: 'Apakah Anda yakin hendak menghapus ' + item.sub_spesialis_nama + '?',
				showDenyButton: true,
				confirmButtonText: `Hapus`,
				denyButtonText: `Batal`,
			}).then(async (result) => {
				/* Read more about isConfirmed, isDenied below */
				if (result.isConfirmed) {
					const response = await axios.delete(`/sub-spesialis/${item.sub_spesialis_id}`)
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
				const response = await axios.delete(`/sub-spesialis/${this.onDeleteItem.sub_spesialis_id}`)
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
		openModalEdit(subSpesialisData) {
			this.modalSubSpesialisOpen = true
			this.subSpesialisData = subSpesialisData
			this.title = 'Ubah'
		},
		openModalTambah() {
			this.modalSubSpesialisOpen = true
			this.subSpesialisData = null
			this.title = 'Tambah'
		},
		async refreshData() {
			this.refreshTable()
		}
	},
	components: { ModalSubSpesialis }
}
</script>
