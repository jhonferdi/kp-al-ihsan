<template>
  <div>
    <page-header :title="`${$metaInfo.title}`" :items="[
      {text: $metaInfo.title, active: true},
    ]" />
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-end mb-4">
          <div>
            <b-button variant="outline-success" :to="{ name: 'str.create' }">
              <b-icon icon="plus"></b-icon> Tambah STR
            </b-button>
          </div>
        </div>
        <b-row class="my-1">
          <b-col sm="6">
            <div class="d-flex align-items-center mb-3">
              <div class="mr-4 text-nowrap">Cari STR</div>
              <b-form-input class="w-100" v-model="ctx.filter" placeholder="Masukan Nomor STR">
              </b-form-input>
            </div>
          </b-col>
        </b-row>
        <b-table hover responsive id="str-table" ref="table" :items="itemsProvider" :current-page="ctx.currentPage"
          :per-page="ctx.perPage" :filter="ctx.filter" :busy="isBusy" :sortBy="ctx.sortBy" :sortDesc="ctx.sortDesc"
          :fields="fields">
          <template #cell(nama_pegawai)="row">
            <span>{{ row.item.nama_pegawai || '-' }}</span>
          </template>
          <template #cell(nomor_str)="row">
            <span>{{ row.item.nomor_str || '-' }}</span>
          </template>
          <template #cell(tanggal_kadaluarsa_str)="row">
            <span>{{ row.item.tanggal_kadaluarsa_str || '-' }}</span>
          </template>
          <template #cell(status)="row">
            <span class="badge badge-pill badge-success" v-if="row.item.status === 1">Active</span>
            <span class="badge badge-pill badge-danger" v-if="row.item.status === 0">Inactive</span>
          </template>
          <!-- <template #cell(image)="row">
            <span>{{ row.item.image || '-' }}</span>
          </template> -->
          <template #cell(actions)="row">
            <nuxt-link :to="{ name: 'str.edit', params: { id: row.item.str_id }}" class="mr-2">
              <b-button variant="outline-primary">
                <b-icon icon="pencil"></b-icon> Edit
              </b-button>
            </nuxt-link>
            <a @click="promptDelete(row.item)" style="cursor: pointer; color: red">
              <b-button variant="outline-danger">
                <b-icon icon="trash"></b-icon>Delete
              </b-button>
            </a>
            <nuxt-link :to="{ name: 'str.detail', params: { id: row.item.str_id }}" class="mr-2">
              <b-button variant="info">
                <b-icon icon="zoom-in"></b-icon>Detail
              </b-button>
            </nuxt-link>
          </template>
        </b-table>
        <b-row>
          <b-col sm="6">
            <b-pagination v-model="ctx.currentPage" :total-rows="str_count" :per-page="ctx.perPage"
              aria-controls="str-table">
            </b-pagination>
          </b-col>
          <b-col sm="6" class="d-flex justify-content-end">
            <span class="font-size">Total Data : {{ str_count }}</span>
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
    return { title: 'STR' }
  },

  async asyncData() {
    if (window.tablectxstr) {
      var ctx = window.tablectxstr
    } else {
      var ctx = {
        currentPage: 1,
        perPage: 20,
        filter: '',
        sortBy: 'nama_pegawai',
        sortDesc: false
      }
    }

    let f1resp = (await axios.get('/str?' + buildQueryParams(ctx))).data
    let str = f1resp.data
    let str_count = f1resp.count

    return {
      str,
      str_count,
      ctx,
    }
  },

  data: () => ({
    fields: [
      { key: 'nama_pegawai', label: 'Nama Pegawai', sortable: true, sortDirection: 'asc' },
      { key: 'nomor_str', label: 'Nomor STR', sortable: false, sortDirection: 'asc' },
      { key: 'tanggal_kadaluarsa_str', label: 'Tanggal Kadaluarsa', sortable: false, sortDirection: 'asc' },
      // { key: 'image', label: 'Image', sortable: false, sortDirection: 'asc' },
      { key: 'status', label: 'Status', sortable: false, sortDirection: 'asc' },
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
        return this.str
      }

      ctx.params = this.ctx.params
      this.isBusy = true

      try {
        window.tablectxstr = ctx
        const response = await axios.get('/str?' + buildQueryParams(ctx))
        this.isBusy = false
        this.str_count = response.data.count
        return response.data.data
      } catch (error) {
        this.isBusy = false
        return []
      }
    },
    async promptDelete(item) {
      Swal.fire({
        title: 'Apakah Anda yakin hendak menghapus ' + item.nomor_str + '?',
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(`/str/${item.str_id}`)
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