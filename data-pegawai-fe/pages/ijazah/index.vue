<template>
  <div>
    <page-header :title="`${$metaInfo.title}`" :items="[
      {text: $metaInfo.title, active: true},
    ]" />
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-end mb-4">
          <div>
            <b-button variant="outline-success" :to="{ name: 'ijazah.create' }">
              <b-icon icon="plus"></b-icon> Tambah Ijazah
            </b-button>
          </div>
        </div>
        <b-row class="my-1">
          <b-col sm="6">
            <div class="d-flex align-items-center mb-3">
              <div class="mr-4 text-nowrap">Cari Ijazah</div>
              <b-form-input class="w-100" v-model="ctx.filter" placeholder="Masukan Nomor Ijazah">
              </b-form-input>
            </div>
          </b-col>
        </b-row>
        <b-table hover responsive id="ijazah-table" ref="table" :items="itemsProvider" :current-page="ctx.currentPage"
          :per-page="ctx.perPage" :filter="ctx.filter" :busy="isBusy" :sortBy="ctx.sortBy" :sortDesc="ctx.sortDesc"
          :fields="fields">
          <template #cell(nomor_ijazah)="row">
            <span>{{ row.item.nomor_ijazah || '-' }}</span>
          </template>
          <template #cell(tanggal_ijazah)="row">
            <span>{{ row.item.tanggal_ijazah || '-' }}</span>
          </template>
          <template #cell(actions)="row">
            <nuxt-link :to="{ name: 'ijazah.edit', params: { id: row.item.ijazah_id }}" class="mr-2">
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
            <b-pagination v-model="ctx.currentPage" :total-rows="ijazah_count" :per-page="ctx.perPage"
              aria-controls="ijazah-table">
            </b-pagination>
          </b-col>
          <b-col sm="6" class="d-flex justify-content-end">
            <span class="font-size">Total Data : {{ ijazah_count }}</span>
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
    return { title: 'Ijazah' }
  },

  async asyncData() {
    if (window.tablectxijazah) {
      var ctx = window.tablectxijazah
    } else {
      var ctx = {
        currentPage: 1,
        perPage: 20,
        filter: '',
        sortBy: 'nomor_ijazah',
        sortDesc: false
      }
    }

    let f1resp = (await axios.get('/ijazah?' + buildQueryParams(ctx))).data
    let ijazah = f1resp.data
    let ijazah_count = f1resp.count

    return {
      ijazah,
      ijazah_count,
      ctx,
    }
  },

  data: () => ({
    fields: [
      { key: 'nomor_ijazah', label: 'Nomor Ijazah', sortable: true, sortDirection: 'asc' },
      { key: 'tanggal_ijazah', label: 'Tanggal Ijazah', sortable: false, sortDirection: 'asc' },
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
        return this.ijazah
      }

      ctx.params = this.ctx.params
      this.isBusy = true

      try {
        window.tablectxijazah = ctx
        const response = await axios.get('/ijazah?' + buildQueryParams(ctx))
        this.isBusy = false
        this.ijazah_count = response.data.count
        return response.data.data
      } catch (error) {
        this.isBusy = false
        return []
      }
    },
    async promptDelete(item) {
      Swal.fire({
        title: 'Apakah Anda yakin hendak menghapus ' + item.nomor_ijazah + '?',
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(`/ijazah/${item.ijazah_id}`)
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