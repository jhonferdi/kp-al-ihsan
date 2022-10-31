<template>
  <div>
    <page-header :title="`${$metaInfo.title}`" :items="[
      {text: $metaInfo.title, active: true},
    ]" />
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-end mb-4">
          <div>
            <b-button variant="outline-success" :to="{ name: 'riwayat-pangkat.create' }">
              <b-icon icon="plus"></b-icon> Tambah Riwayat Pangkat
            </b-button>
          </div>
        </div>
        <b-row class="my-1">
          <b-col sm="6">
            <div class="d-flex align-items-center mb-3">
              <div class="mr-4 text-nowrap">Cari Riwayat Pangkat</div>
              <b-form-input class="w-100" v-model="ctx.filter" placeholder="Masukan Nomor SK">
              </b-form-input>
            </div>
          </b-col>
        </b-row>
        <b-table hover responsive id="riwayat-pangkat-table" ref="table" :items="itemsProvider"
          :current-page="ctx.currentPage" :per-page="ctx.perPage" :filter="ctx.filter" :busy="isBusy"
          :sortBy="ctx.sortBy" :sortDesc="ctx.sortDesc" :fields="fields">
          <template #cell(pid)="row">
            <span>{{ row.item.pegawai ? row.item.pegawai.peg_nama_lengkap : '' }}</span>
          </template>
          <template #cell(tgl_pangkat)="row">
            <span>{{ row.item.tgl_pangkat || '-' }}</span>
          </template>
          <template #cell(nomor_sk)="row">
            <span>{{ row.item.nomor_sk || '-' }}</span>
          </template>
          <template #cell(actions)="row">
            <nuxt-link :to="{ name: 'riwayat-pangkat.edit', params: { id: row.item.riw_pangkat_id }}" class="mr-2">
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
            <b-pagination v-model="ctx.currentPage" :total-rows="riwayat_pangkat_count" :per-page="ctx.perPage"
              aria-controls="riwayat-pangkat-table">
            </b-pagination>
          </b-col>
          <b-col sm="6" class="d-flex justify-content-end">
            <span class="font-size">Total Data : {{ riwayat_pangkat_count }}</span>
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
    return { title: 'Riwayat Pangkat' }
  },

  async asyncData() {
    if (window.tablectxriwpang) {
      var ctx = window.tablectxriwpang
    } else {
      var ctx = {
        currentPage: 1,
        perPage: 20,
        filter: '',
        sortBy: 'nomor_sk',
        sortDesc: false
      }
    }

    let f1resp = (await axios.get('/riwayat-pangkat?' + buildQueryParams(ctx))).data
    let riwayat_pangkat = f1resp.data
    let riwayat_pangkat_count = f1resp.count

    return {
      riwayat_pangkat,
      riwayat_pangkat_count,
      ctx,
    }
  },

  data: () => ({
    fields: [
      { key: 'pid', label: 'Nama Pegawai', sortable: false, sortDirection: 'asc' },
      { key: 'tgl_pangkat', label: 'Tanggal Pangkat', sortable: false, sortDirection: 'asc' },
      { key: 'nomor_sk', label: 'Nomor SK', sortable: true, sortDirection: 'asc' },
      { key: 'actions', label: 'Actions' }
    ],
    isTableInit: false,
    isBusy: false
  }),

  created: function () {
  },

  watch: {
  },

  methods: {
    async itemsProvider(ctx) {
      if (!this.isTableInit) {
        this.isTableInit = true
        return this.riwayat_pangkat
      }

      ctx.params = this.ctx.params
      this.isBusy = true

      try {
        window.tablectxriwpang = ctx
        const response = await axios.get('/riwayat-pangkat?' + buildQueryParams(ctx))
        this.isBusy = false
        this.riwayat_pangkat_count = response.data.count
        return response.data.data
      } catch (error) {
        this.isBusy = false
        return []
      }
    },
    async promptDelete(item) {
      Swal.fire({
        title: 'Apakah Anda yakin hendak menghapus ' + item.nomor_sk + '?',
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(`/riwayat-pangkat/${item.riw_pangkat_id}`)
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