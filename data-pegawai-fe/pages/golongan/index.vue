<template>
  <div>
    <page-header :title="`${$metaInfo.title}`" :items="[
      {text: $metaInfo.title, active: true},
    ]" />
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-end mb-4">
          <div>
            <b-button variant="outline-success" :to="{ name: 'golongan.create' }">
              <b-icon icon="plus"></b-icon> Tambah Golongan
            </b-button>
          </div>
        </div>
        <b-row class="my-1">
          <b-col sm="6">
            <div class="d-flex align-items-center mb-3">
              <div class="mr-4 text-nowrap">Cari Golongan</div>
              <b-form-input class="w-100" v-model="ctx.filter" placeholder="Masukan Golongan">
              </b-form-input>
            </div>
          </b-col>
        </b-row>
        <b-table hover responsive id="golongan-table" ref="table" :items="itemsProvider" :current-page="ctx.currentPage"
          :per-page="ctx.perPage" :filter="ctx.filter" :busy="isBusy" :sortBy="ctx.sortBy" :sortDesc="ctx.sortDesc"
          :fields="fields">
          <template #cell(golongan_nama)="row">
            <span>{{ row.item.golongan_nama || '-' }}</span>
          </template>
          <template #cell(actions)="row">
            <nuxt-link :to="{ name: 'golongan.edit', params: { id: row.item.golongan_id }}" class="mr-2">
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
            <b-pagination v-model="ctx.currentPage" :total-rows="golongan_count" :per-page="ctx.perPage"
              aria-controls="golongan-table">
            </b-pagination>
          </b-col>
          <b-col sm="6" class="d-flex justify-content-end">
            <span class="font-size">Total Data : {{ golongan_count }}</span>
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
    return { title: 'Golongan' }
  },

  async asyncData() {
    if (window.tablectxgolongan) {
      var ctx = window.tablectxgolongan
    } else {
      var ctx = {
        currentPage: 1,
        perPage: 20,
        filter: '',
        sortBy: 'golongan_nama',
        sortDesc: false
      }
    }

    let f1resp = (await axios.get('/golongan?' + buildQueryParams(ctx))).data
    let golongan = f1resp.data
    let golongan_count = f1resp.count

    return {
      golongan,
      golongan_count,
      ctx,
    }
  },

  data: () => ({
    fields: [
      { key: 'golongan_nama', label: 'Nama Golongan', sortable: true, sortDirection: 'asc' },
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
        return this.golongan
      }

      ctx.params = this.ctx.params
      this.isBusy = true

      try {
        window.tablectxgolongan = ctx
        const response = await axios.get('/golongan?' + buildQueryParams(ctx))
        this.isBusy = false
        this.golongan_count = response.data.count
        return response.data.data
      } catch (error) {
        this.isBusy = false
        return []
      }
    },
    async promptDelete(item) {
      Swal.fire({
        title: 'Apakah Anda yakin hendak menghapus ' + item.golongan_nama + '?',
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(`/golongan/${item.golongan_id}`)
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
