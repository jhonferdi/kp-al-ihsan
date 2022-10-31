<template>
  <div>
    <page-header :title="`${$metaInfo.title}`" :items="[
      {text: $metaInfo.title, active: true},
    ]" />
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-end mb-4">
          <div>
            <b-button variant="outline-success" :to="{ name: 'jenjang-jabatan.create' }">
              <b-icon icon="plus"></b-icon> Tambah Jenjang Jabatan
            </b-button>
          </div>
        </div>
        <b-row class="my-1">
          <b-col sm="6">
            <div class="d-flex align-items-center mb-3">
              <div class="mr-4 text-nowrap">Cari Jenjang Jabatan</div>
              <b-form-input class="w-100" v-model="ctx.filter" placeholder="Masukan Jenjang Jabatan">
              </b-form-input>
            </div>
          </b-col>
        </b-row>
        <b-table hover responsive id="jenjang-jabatan-table" ref="table" :items="itemsProvider"
          :current-page="ctx.currentPage" :per-page="ctx.perPage" :filter="ctx.filter" :busy="isBusy"
          :sortBy="ctx.sortBy" :sortDesc="ctx.sortDesc" :fields="fields">
          <template #cell(jenjang_jabatan_nama)="row">
            <span>{{ row.item.jenjang_jabatan_nama || '-' }}</span>
          </template>
          <template #cell(actions)="row">
            <nuxt-link :to="{ name: 'jenjang-jabatan.edit', params: { id: row.item.jenjang_jabatan_id }}" class="mr-2">
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
            <b-pagination v-model="ctx.currentPage" :total-rows="pegawai_count" :per-page="ctx.perPage"
              aria-controls="jenjang-jabatan-table">
            </b-pagination>
          </b-col>
          <b-col sm="6" class="d-flex justify-content-end">
            <span class="font-size">Total Data : {{ jenjang_jabatan_count }}</span>
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
    return { title: 'Jenjang Jabatan' }
  },

  async asyncData() {
    if (window.tablectxjenjangjab) {
      var ctx = window.tablectxjenjangjab
    } else {
      var ctx = {
        currentPage: 1,
        perPage: 20,
        filter: '',
        sortBy: 'jenjang_jabatan_nama',
        sortDesc: false
      }
    }

    let f1resp = (await axios.get('/jenjang-jabatan?' + buildQueryParams(ctx))).data
    let jenjang_jabatan = f1resp.data
    let jenjang_jabatan_count = f1resp.count

    return {
      jenjang_jabatan,
      jenjang_jabatan_count,
      ctx,
    }
  },

  data: () => ({
    fields: [
      { key: 'jenjang_jabatan_nama', label: 'Nama Jenjang Jabatan', sortable: true, sortDirection: 'asc' },
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
        return this.jenjang_jabatan
      }

      ctx.params = this.ctx.params
      this.isBusy = true

      try {
        window.tablectxjenjangjab = ctx
        const response = await axios.get('/jenjang-jabatan?' + buildQueryParams(ctx))
        this.isBusy = false
        this.jenjang_jabatan_count = response.data.count
        return response.data.data
      } catch (error) {
        this.isBusy = false
        return []
      }
    },
    async promptDelete(item) {
      Swal.fire({
        title: 'Apakah Anda yakin hendak menghapus ' + item.jenjang_jabatan_nama + '?',
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(`/jenjang-jabatan/${item.jenjang_jabatan_id}`)
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