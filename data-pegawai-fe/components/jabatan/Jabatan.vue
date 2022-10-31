<template>
	<div>
	  <div class="d-sm-inline-flex mb-3">
		<div class="d-sm-inline-flex">
		  <b-input-group class="mb-2 mb-sm-0 d-flex" style="max-width:230px">
			<b-form-input ref="search" @keyup.enter="updateSearch()" :value="ctx.filter"
			  placeholder="Cari nama jabatan"></b-form-input>
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
		<!-- <b-button variant="darkgreen" :to="{ name: 'jabatan.create' }" size="sm"
		  class="text-nowrap mr-2 px-3 d-inline-flex align-items-center">Tambah Jabatan</b-button> -->
		<b-button variant="darkgreen" @click="openModalTambah()" size="sm"
		  class="text-nowrap mr-2 px-3 d-inline-flex align-items-center">Tambah Jabatan</b-button>
		<ModalJabatan :open="modalJabatanOpen" :jabatan="jabatanData" size="xs" :title="`${title} Jabatan`"
		  @close="modalJabatanOpen = false" @onSave="refreshData"/>
	  </div>
	  <b-table class="table-rounded" striped hover responsive head-variant="darkgreen" id="jabatan-table" ref="table"
		:items="itemsProvider" :current-page="ctx.currentPage" :per-page="ctx.perPage" :filter="ctx.filter" :busy="isBusy"
		:sortBy="ctx.sortBy" :sortDesc="ctx.sortDesc" :fields="fields" show-empty
		:empty-text="'Tidak ada jabatan ditemukan'"
		:empty-filtered-text="`Tidak ada jabatan bernama '${ctx.filter}'`">
		<template #cell(index)="data">
		  {{ data.index + ctx.perPage * (ctx.currentPage - 1) + 1 }}
		</template>
    <template #cell(jabatan_nama)="row">
      <span>{{ row.item.jabatan_nama || '-' }}</span>
    </template>
    <template #cell(jf)="row">
      <span>{{ row.item.jabatan_fungsional ? row.item.jabatan_fungsional.jabatan_fungsional_nama : '' }}</span>
    </template>
    <!-- <template #cell(uk)="row">
      <span>{{ row.item.unit_kerja ? row.item.unit_kerja.unit_kerja_nama : '' }}</span>
    </template> -->
		<template #cell(actions)="row">
		  <div class="text-nowrap">
			<!-- <nuxt-link :to="{ name: 'jabatan.edit', params: { id: row.item.jabatan_id }}"
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
	  <modal-hapus name="jabatan" :open="modalHapusOpen" @close="modalHapusOpen = false" @delete="deleteItem" />
	  <TablePagination v-model="ctx.currentPage" :itemsLength="jabatans_count" @perPage="getPerPage" />
	</div>
  </template>
<script>
  import axios from 'axios'
  import Swal from 'sweetalert2'
  import { buildQueryParams } from '~/plugins/utils'
  import ModalJabatan from '../modals/ModalJabatan.vue'
  
  export default {
	middleware: ["auth"],
	data: () => ({
	  ctx: {},
	  jabatans: null,
	  jabatans_count: null,
	  fields: [
		  { key: "index", label: "No", thClass: 'bg-darkgreen text-white' },
		  { key: 'jabatan_nama', label: 'Nama Jabatan', tdClass: 'w-50', sortable: true, sortDirection: 'asc', thClass: 'bg-darkgreen text-white' },
		  { key: 'jf', label: 'Jabatan Fungsional', tdClass: 'w-50', sortable: false, sortDirection: 'asc', thClass: 'bg-darkgreen text-white' },
    // { key: 'uk', label: 'Unit Kerja Struktural', sortable: false, sortDirection: 'asc' },
		  { key: "actions", label: "Aksi", thClass: 'bg-darkgreen text-white' }
	  ],
	  isTableInit: false,
	  isBusy: false,
	  modalJabatanOpen: false,
	  modalHapusOpen: false,
	  onDeleteItem: null,
      jabatanData: null,
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
			if (window.tablectxjabatan) {
        this.ctx = window.tablectxjabatan
      } else {
        this.ctx = {
          currentPage: 1,
          perPage: 20,
          filter: '',
          sortBy: 'jabatan_nama',
          sortDesc: false
        }
      }

      let f1resp = (await axios.get('/jabatan?' + buildQueryParams(ctx))).data
      this.jabatans = f1resp.data
      this.jabatans_count = f1resp.count
		
	  },
	  async itemsProvider(ctx) {
      if (!this.isTableInit) {
        this.isTableInit = true
        return this.jabatans
      }

      ctx.params = this.ctx.params
      this.isBusy = true

      try {
        window.tablectxjabatan = ctx
        const response = await axios.get('/jabatan?' + buildQueryParams(ctx))
        this.isBusy = false
        this.jabatans_count = response.data.count
        return response.data.data
      } catch (error) {
        this.isBusy = false
        return []
      }
    },
    async promptDelete(item) {
      Swal.fire({
        title: 'Apakah Anda yakin hendak menghapus ' + item.jabatan_nama + '?',
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(`/jabatan/${item.jabatan_id}`)
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
      const response = await axios.delete(`/jabatan/${this.onDeleteItem.jabatan_id}`)
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
      openModalEdit(jabatanData) {
      this.modalJabatanOpen = true
      this.jabatanData = jabatanData
      this.title = 'Ubah'
    },
      openModalTambah() {
      this.modalJabatanOpen = true
      this.jabatanData = null
      this.title = 'Tambah'
    },
    async refreshData() {
      this.refreshTable()
    },
	},
	components: { ModalJabatan }
  }
</script>
