<template>
  <div>
    <page-header :title="`${$metaInfo.title}`" :items="[
      {text: $metaInfo.title, active: true},
    ]" />
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-end mb-4">
          <div>
            <b-button variant="outline-success" :to="{ name: 'kualifikasi-pendidikan.create' }">
              <b-icon icon="plus"></b-icon> Tambah Kualifikasi Pendidikan
            </b-button>
          </div>
        </div>
        <b-row class="my-1">
          <b-col sm="6">
            <div class="d-flex align-items-center mb-3">
              <div class="mr-4 text-nowrap">Cari Kualifikasi Pendidikan</div>
              <b-form-input class="w-100" v-model="ctx.filter" placeholder="Masukan Kualifikasi Pendidikan">
              </b-form-input>
            </div>
          </b-col>
        </b-row>
        <b-table hover responsive id="kualifikasi-pendidikan-table" ref="table" :items="itemsProvider"
          :current-page="ctx.currentPage" :per-page="ctx.perPage" :filter="ctx.filter" :busy="isBusy"
          :sortBy="ctx.sortBy" :sortDesc="ctx.sortDesc" :fields="fields">
          <template #cell(kualifikasi_pendidikan)="row">
            <span>{{ row.item.kualifikasi_pendidikan || '-' }}</span>
          </template>
          <template #cell(actions)="row">
            <nuxt-link :to="{ name: 'kualifikasi-pendidikan.edit', params: { id: row.item.kualifikasi_pendidikan_id }}"
              class="mr-2">
              <b-button variant="outline-primary">
                <b-icon icon="pencil"></b-icon> Edit
              </b-button>
            </nuxt-link>
            <a @click="promptDelete(row.item)" style="cursor: pointer; color: red">
              <b-button variant="outline-danger">
                <b-icon icon="trash"></b-icon>Delete
              </b-button>>
            </a>
          </template>
        </b-table>
        <b-row>
          <b-col sm="6">
            <b-pagination v-model="ctx.currentPage" :total-rows="kualifikasi_pendidikan_count" :per-page="ctx.perPage"
              aria-controls="kualifikasi-pendidikan-table">
            </b-pagination>
          </b-col>
          <b-col sm="6" class="d-flex justify-content-end">
            <span class="font-size">Total Data : {{ kualifikasi_pendidikan_count }}</span>
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
    return { title: 'Kualifikasi Pendidikan' }
  },

  async asyncData() {
    if (window.tablectxkualifikasi) {
      var ctx = window.tablectxkualifikasi
    } else {
      var ctx = {
        currentPage: 1,
        perPage: 20,
        filter: '',
        sortBy: 'kualifikasi_pendidikan',
        sortDesc: false
      }
    }

    let f1resp = (await axios.get('/kualifikasi-pendidikan?' + buildQueryParams(ctx))).data
    let kualifikasi_pendidikan = f1resp.data
    let kualifikasi_pendidikan_count = f1resp.count

    return {
      kualifikasi_pendidikan,
      kualifikasi_pendidikan_count,
      ctx,
    }
  },

  data: () => ({
    fields: [
      { key: 'kualifikasi_pendidikan', label: 'Nama Kualifikasi Pendidikan', sortable: true, sortDirection: 'asc' },
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
        return this.kualifikasi_pendidikan
      }

      ctx.params = this.ctx.params
      this.isBusy = true

      try {
        window.tablectxkualifikasi = ctx
        const response = await axios.get('/kualifikasi-pendidikan?' + buildQueryParams(ctx))
        this.isBusy = false
        this.kualifikasi_pendidikan_count = response.data.count
        return response.data.data
      } catch (error) {
        this.isBusy = false
        return []
      }
    },
    async promptDelete(item) {
      Swal.fire({
        title: 'Apakah Anda yakin hendak menghapus ' + item.kualifikasi_pendidikan + '?',
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(`/kualifikasi-pendidikan/${item.kualifikasi_pendidikan_id}`)
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
