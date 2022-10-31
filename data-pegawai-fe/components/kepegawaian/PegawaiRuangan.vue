<template>
  <div>
    <div class="d-sm-inline-flex mb-3">
      <div class="d-sm-inline-flex">
        <b-input-group class="mb-2 mb-sm-0 d-flex" style="max-width:230px">
          <b-form-input ref="search" @keyup.enter="updateSearch()" :value="ctx.filter"
            placeholder="Cari pegawai ruangan"></b-form-input>
          <b-input-group-append>
            <span>
              <b-button variant="darkgreen" @click="updateSearch()">
                <b-icon icon="search" />
              </b-button>
            </span>
          </b-input-group-append>
        </b-input-group>
        <div class="ml-sm-3 d-inline-block" :style="[{width: ruangOptions.length > 0 ?'400px':'auto'}]"
          style="min-height:36.53px; max-width:100%">
          <v-select v-if="ruangOptions.length > 0" :options="ruangOptions" :reduce="item => item.ruangan_id"
            label="nama_ruangan" placeholder="Semua Ruangan" v-model="ctx.params.ruangan_id">
          </v-select>
        </div>
      </div>
      <div class="mx-3 d-none d-sm-block border-left" style="border-color:#E0E0E0 !important"></div>
      <!-- <b-button variant="darkgreen" :to="{ name: 'pegawai-ruangan.create' }" size="sm"
        class="text-nowrap mr-2 px-3 d-inline-flex align-items-center">Tambah Pegawai Ruangan</b-button> -->
      <b-button variant="darkgreen" @click="openModalTambah()" size="sm"
        class="text-nowrap mr-2 px-3 d-inline-flex align-items-center">Tambah Pegawai Ruangan</b-button>
      <ModalPegawaiRuangan :open="modalPegawaiRuanganOpen" :pegawaiRuangan="pegawaiRuanganData" size="xs" :title="`${title} Pegawai Ruangan`"
        @close="modalPegawaiRuanganOpen = false" @onSave="refreshData"/>
    </div>
    <b-table class="table-rounded" striped hover responsive head-variant="darkgreen" id="pegawai-ruangan-table" ref="table"
      :items="itemsProvider" :current-page="ctx.currentPage" :per-page="ctx.perPage" :filter="ctx.filter" :busy="isBusy"
      :sortBy="ctx.sortBy" :sortDesc="ctx.sortDesc" :fields="fields" show-empty
      :empty-text="'Tidak ada pegawai ruangan ditemukan'"
      :empty-filtered-text="`Tidak ada pegawai ruangan bernama '${ctx.filter}'`">
      <template #cell(index)="data">
        {{ data.index + ctx.perPage * (ctx.currentPage - 1) + 1 }}
      </template>

      <template #cell(nama_pegawai)="row">
        <span>{{ row.item.pegawai ? row.item.pegawai.peg_nama_lengkap : '' }}</span>
      </template>
      <template #cell(rid)="row">
        <span>{{ row.item.ruangan ? row.item.ruangan.nama_ruangan : '' }}</span>
      </template>
      <template #cell(role)="row">
        <span>{{ row.item.role_ruangan ? row.item.role_ruangan.role : '-' }}</span>
      </template>
      <template #cell(actions)="row">
        <div class="text-nowrap">
          <!-- <nuxt-link :to="{ name: 'pegawai-ruangan.edit', params: { id: row.item.peg_ruangan_id }}"
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
    <modal-hapus name="pegawai ruangan" :open="modalHapusOpen" @close="modalHapusOpen = false" @delete="deleteItem" />
    <TablePagination v-model="ctx.currentPage" :itemsLength="pegawai_ruangan_count" @perPage="getPerPage" />
  </div>
</template>
<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { buildQueryParams } from '~/plugins/utils'
import ModalPegawaiRuangan from '../modals/ModalPegawaiRuangan.vue'

export default {
  middleware: ["auth"],
  data: () => ({
    ctx: {},
    pegawai_ruangan: null,
    pegawai_ruangan_count: null,
    ruangOptions: [],
    fields: [
      { key: "index", label: "No", thClass: 'bg-darkgreen text-white' },
      { key: 'nama_pegawai', label: 'Nama Pegawai', sortable: true, sortDirection: 'asc', thClass: 'bg-darkgreen text-white' },
      { key: 'rid', label: 'Ruangan', sortable: false, sortDirection: 'asc', thClass: 'bg-darkgreen text-white' },
      { key: 'role', label: 'Role', sortable: false, sortDirection: 'asc', thClass: 'bg-darkgreen text-white' },
      { key: "actions", label: "Aksi", thClass: 'bg-darkgreen text-white' }
    ],
    isTableInit: false,
    isBusy: false,
    selected: [],
    modalPegawaiRuanganOpen: false,
    modalHapusOpen: false,
    onDeleteItem: null,
    pegawaiRuanganData: null,
    title: null,
  }),
  mounted: function () {
    this.init();
  },
  watch: {
    'ctx.params.ruangan_id'() {
      this.refreshTable()
    }
  },
  // computed: {
  //   selectedItems: function(){
  //     return this.pegawai.filter(function(ctx){
  //     })
  //   }
  // },
  methods: {
    async init() {
      if (window.tablectxpegruang) {
        this.ctx = window.tablectxpegruang
      } else {
        this.ctx = {
          currentPage: 1,
          perPage: 20,
          filter: '',
          sortBy: 'nama_pegawai',
          sortDesc: false,
          params: {
            ruangan_id: ''
          }
        }
      }

      let f1resp = (await axios.get('/pegawai-ruangan?' + buildQueryParams(this.ctx))).data
      let f2resp = (await axios.get('/ruangan')).data
      this.pegawai_ruangan = f1resp.data
      this.pegawai_ruangan_count = f1resp.count
      this.ruangOptions = f2resp.data
      this.ruangOptions = [...this.ruangOptions, { id: 0, name: 'Belum diset' }]
    },
    async itemsProvider(ctx) {
      if (!this.isTableInit) {
        this.isTableInit = true
        return this.pegawai_ruangan
      }

      ctx.params = this.ctx.params
      this.isBusy = true

      try {
        window.tablectxpegruang = ctx
        const response = await axios.get('/pegawai-ruangan?' + buildQueryParams(ctx))
        this.isBusy = false
        this.pegawai_ruangan_count = response.data.count
        return response.data.data
      } catch (error) {
        this.isBusy = false
        return []
      }
    },
    async promptDelete(item) {
      Swal.fire({
        title: 'Apakah Anda yakin hendak menghapus ' + item.nama_pegawai + '?',
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(`/pegawai-ruangan/${item.peg_ruangan_id}`)
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
        const response = await axios.delete(`/pegawai-ruangan/${this.onDeleteItem.peg_ruangan_id}`)
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
      openModalEdit(pegawaiRuanganData) {
          this.modalPegawaiRuanganOpen = true
          this.pegawaiRuanganData = pegawaiRuanganData
          this.title = 'Ubah'
      },
      openModalTambah() {
          this.modalPegawaiRuanganOpen = true
          this.pegawaiRuanganData = null
          this.title = 'Tambah'
      },
      async refreshData() {
          this.refreshTable()
      },
  },
  components: { ModalPegawaiRuangan }
}
</script>
  