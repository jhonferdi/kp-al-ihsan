<template>
  <div>
    <page-header :title="`${$metaInfo.title}`" :items="[
      {text: $metaInfo.title, active: true},
    ]" />
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-end mb-4">
          <div>
            <b-button variant="outline-success" :to="{ name: 'pegawai-ruangan.create' }">
              <b-icon icon="plus"></b-icon> Tambah Pegawai Ruangan
            </b-button>
          </div>
        </div>
        <b-row class="my-1">
          <b-col sm="6">
            <div class="d-flex align-items-center mb-3">
              <div class="mr-4 text-nowrap">Masukan Ruangan</div>
              <v-select :options="ruangOptions" :reduce="item => item.ruangan_id" label="nama_ruangan"
                placeholder="Semua Ruangan" v-model="ctx.params.ruangan_id" style="width: 100%;">
              </v-select>
            </div>
          </b-col>
          <b-col sm="6">
            <div class="d-flex align-items-center mb-3">
              <div class="mr-4 text-nowrap">Cari Pegawai Ruangan</div>
              <b-form-input class="w-100" v-model="ctx.filter" placeholder="Masukan Pegawai Ruangan">
              </b-form-input>
            </div>
          </b-col>
        </b-row>

        <b-table hover responsive id="pegawai-ruangan-table" ref="table" :items="itemsProvider"
          :current-page="ctx.currentPage" :per-page="ctx.perPage" :filter="ctx.filter" :busy="isBusy"
          :sortBy="ctx.sortBy" :sortDesc="ctx.sortDesc" :fields="fields">
          <template #cell(pid)="row">
            <span>{{ row.item.pegawai ? row.item.pegawai.peg_nama_lengkap : '' }}</span>
          </template>
          <template #cell(rid)="row">
            <span>{{ row.item.ruangan ? row.item.ruangan.nama_ruangan : '' }}</span>
          </template>
          <template #cell(role)="row">
            <span>{{ row.item.role || '-' }}</span>
          </template>
          <template #cell(actions)="row">
            <nuxt-link :to="{ name: 'pegawai-ruangan.edit', params: { id: row.item.peg_ruangan_id }}" class="mr-2">
              <b-button variant="outline-primary">
                <b-icon icon="pencil"></b-icon> Edit
              </b-button>
            </nuxt-link>
            <a @click="promptDelete(row.item)" style="cursor: pointer; color: red">
              <b-button variant="outline-danger">
                <b-icon icon="trash"></b-icon>Delete
              </b-button>
            </a>
          </template>
        </b-table>
        <b-row>
          <b-col sm="6">
            <b-pagination v-model="ctx.currentPage" :total-rows="pegawai_ruangan_count" :per-page="ctx.perPage"
              aria-controls="pegawai-ruangan-table">
            </b-pagination>
          </b-col>
          <b-col sm="6" class="d-flex justify-content-end">
            <span class="font-size">Total Data : {{ pegawai_ruangan_count }}</span>
          </b-col>
        </b-row>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { buildQueryParams } from '~/plugins/utils'

export default {
  //   middleware: ['auth', 'check-permission'],
  middleware: ['auth'],


  head() {
    return { title: 'Pegawai Ruangan' }
  },

  async asyncData() {
    if (window.tablectxpegruang) {
      var ctx = window.tablectxpegruang
    } else {
      var ctx = {
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

    let f1resp = (await axios.get('/pegawai-ruangan?' + buildQueryParams(ctx))).data
    let f2resp = (await axios.get('/ruangan')).data
    let pegawai_ruangan = f1resp.data
    let pegawai_ruangan_count = f1resp.count
    let ruangOptions = f2resp.data


    return {
      pegawai_ruangan,
      pegawai_ruangan_count,
      ctx,
      ruangOptions: [...ruangOptions, { id: 0, name: 'Belum diset' }]
    }
  },

  data: () => ({
    fields: [
      { key: 'pid', label: 'Nama Pegawai', sortable: true, sortDirection: 'asc' },
      { key: 'rid', label: 'Ruangan', sortable: false, sortDirection: 'asc' },
      { key: 'role', label: 'Role', sortable: false, sortDirection: 'asc' },
      { key: 'actions', label: 'Actions' }
    ],
    isTableInit: false,
    isBusy: false,
    selected: []
  }),

  created: function () {
  },

  watch: {
    'ctx.params.ruangan_id'() {
      this.refreshTable()
    }
  },

  methods: {
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
    refreshTable() {
      this.$refs.table.refresh()
    }
  }
}
</script>
<style>
.font-size {
  font-size: 1rem;
}
</style>
