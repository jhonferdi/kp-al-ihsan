<template>
	<div>
	  <div class="d-sm-inline-flex mb-3">
		<div class="d-sm-inline-flex">
		  <b-input-group class="mb-2 mb-sm-0 d-flex" style="max-width:230px">
			<b-form-input ref="search" @keyup.enter="updateSearch()" :value="ctx.filter"
			  placeholder="Cari gelar pendidikan"></b-form-input>
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
		<!-- <b-button variant="darkgreen" :to="{ name: 'pendidikan.create' }" size="sm"
		  class="text-nowrap mr-2 px-3 d-inline-flex align-items-center">Tambah Pendidikan</b-button> -->
		<b-button variant="darkgreen" @click="openModalTambah()" size="sm"
		  class="text-nowrap mr-2 px-3 d-inline-flex align-items-center">Tambah Pendidikan</b-button>
		<ModalPendidikan :open="modalPendidikanOpen" :pendidikan="pendidikanData" size="xs" :title="`${title} Gelar Pendidikan`"
		  @close="modalPendidikanOpen = false" @onSave="refreshData" />
	  </div>
	  <b-table class="table-rounded" striped hover responsive head-variant="darkgreen" id="gelar-pendidikan-table" ref="table"
		:items="itemsProvider" :current-page="ctx.currentPage" :per-page="ctx.perPage" :filter="ctx.filter" :busy="isBusy"
		:sortBy="ctx.sortBy" :sortDesc="ctx.sortDesc" :fields="fields" show-empty
		:empty-text="'Tidak ada pendidikan ditemukan'"
		:empty-filtered-text="`Tidak ada pendidikan bernama '${ctx.filter}'`">
		<template #cell(index)="data">
		  {{ data.index + ctx.perPage * (ctx.currentPage - 1) + 1 }}
		</template>
		<template #cell(pendidikan_nama)="row">
			<span>{{ row.item.pendidikan_nama || '-' }}</span>
		</template>
		<template #cell(actions)="row">
		  <div class="text-nowrap">
			<!-- <nuxt-link :to="{ name: 'pendidikan.edit', params: { id: row.item.pendidikan_id }}"
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
	  <modal-hapus name="gelar pendidikan" :open="modalHapusOpen" @close="modalHapusOpen = false" @delete="deleteItem" />
	  <TablePagination v-model="ctx.currentPage" :itemsLength="pendidikan_count" @perPage="getPerPage" />
	</div>
  </template>
<script>
  import axios from 'axios'
  import Swal from 'sweetalert2'
  import { buildQueryParams } from '~/plugins/utils'
  import ModalPendidikan from '../modals/ModalPendidikan.vue'
  
  export default {
	middleware: ["auth"],
	data: () => ({
	  ctx: {},
	  pendidikan: null,
	  pendidikan_count: null,
	  fields: [
		  { key: "index", label: "No", thClass: 'bg-darkgreen text-white' },
		  { key: 'pendidikan_nama', tdClass: 'w-100', label: 'Nama Pendidikan', sortable: true, sortDirection: 'asc', thClass: 'bg-darkgreen text-white' },
		  { key: "actions", label: "Aksi", thClass: 'bg-darkgreen text-white' }
	  ],
	  isTableInit: false,
	  isBusy: false,
	  modalPendidikanOpen: false,
	  modalHapusOpen: false,
	  onDeleteItem: null,
	  pendidikanData: null,
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
			if (window.tablectxpendidikan) {
				this.ctx = window.tablectxpendidikan
			} else {
				this.ctx = {
					currentPage: 1,
					perPage: 20,
					filter: '',
					sortBy: 'pendidikan_nama',
					sortDesc: false
				}
			}

			let f1resp = (await axios.get('/pendidikan?' + buildQueryParams(ctx))).data
			this.pendidikan = f1resp.data
			this.pendidikan_count = f1resp.count
		
	  },
	  async itemsProvider(ctx) {
      if (!this.isTableInit) {
        this.isTableInit = true
        return this.pendidikan
      }

      ctx.params = this.ctx.params
      this.isBusy = true

      try {
        window.tablectxpendidikan = ctx
        const response = await axios.get('/pendidikan?' + buildQueryParams(ctx))
        this.isBusy = false
        this.pendidikan_count = response.data.count
        return response.data.data
      } catch (error) {
        this.isBusy = false
        return []
      }
    },
    async promptDelete(item) {
      Swal.fire({
        title: 'Apakah Anda yakin hendak menghapus ' + item.pendidikan_nama + '?',
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(`/pendidikan/${item.pendidikan_id}`)
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
      const response = await axios.delete(`/pendidikan/${this.onDeleteItem.pendidikan_id}`)
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
	  openModalEdit(pendidikanData) {
      this.modalPendidikanOpen = true
      this.pendidikanData = pendidikanData
      this.title = 'Ubah'
    },
      openModalTambah() {
      this.modalPendidikanOpen = true
      this.pendidikanData = null
      this.title = 'Tambah'
    },
    async refreshData() {
      this.refreshTable()
    }
	},
	components: { ModalPendidikan }
  }
</script>
