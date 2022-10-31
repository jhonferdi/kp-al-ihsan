<template>
  <div>
    <page-header :title="`${$metaInfo.title}`" :items="[
      {text: $metaInfo.title, active: true},
    ]" />
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-end mb-4">
          <div>
            <b-button variant="outline-success" :to="{ name: 'status-pegawai.create' }">
              <b-icon icon="plus"></b-icon> Tambah Status Pegawai
            </b-button>
          </div>
        </div>
        <b-row class="my-1">
          <b-col sm="6">
            <div class="d-flex align-items-center mb-3">
              <div class="mr-4 text-nowrap">Cari Status Kepegawaian</div>
              <b-form-input class="w-100" v-model="ctx.filter" placeholder="Masukan Nama Status Kepegawaian">
              </b-form-input>
            </div>
          </b-col>
        </b-row>
        <b-table hover responsive id="status-pegawai-table" ref="table" :items="itemsProvider"
          :current-page="ctx.currentPage" :per-page="ctx.perPage" :filter="ctx.filter" :busy="isBusy"
          :sortBy="ctx.sortBy" :sortDesc="ctx.sortDesc" :fields="fields">
          <template #cell(status_kepegawaian)="row">
            <span>{{ row.item.status_kepegawaian || '-' }}</span>
          </template>
          <template #cell(actions)="row">
            <nuxt-link :to="{ name: 'status-pegawai.edit', params: { id: row.item.status_kepegawaian_id }}"
              class="mr-2">
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
            <b-pagination v-model="ctx.currentPage" :total-rows="status_pegawai_count" :per-page="ctx.perPage"
              aria-controls="status-pegawai-table">
            </b-pagination>
          </b-col>
          <b-col sm="6" class="d-flex justify-content-end">
            <span class="font-size">Total Data : {{ status_pegawai_count }}</span>
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
    return { title: 'Status Kepegawaian' }
  },

  async asyncData() {
    if (window.tablectxstatuspeg) {
      var ctx = window.tablectxstatuspeg
    } else {
      var ctx = {
        currentPage: 1,
        perPage: 20,
        filter: '',
        sortBy: 'status_kepegawaian',
        sortDesc: false
      }
    }

    let f1resp = (await axios.get('/status-pegawai?' + buildQueryParams(ctx))).data
    let status_pegawai = f1resp.data
    let status_pegawai_count = f1resp.count

    return {
      status_pegawai,
      status_pegawai_count,
      ctx,
    }
  },

  data: () => ({
    fields: [
      { key: 'status_kepegawaian', label: 'Nama Status Kepegawaian', sortable: true, sortDirection: 'asc' },
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
        return this.status_pegawai
      }

      ctx.params = this.ctx.params
      this.isBusy = true

      try {
        window.tablectxstatuspeg = ctx
        const response = await axios.get('/status-pegawai?' + buildQueryParams(ctx))
        this.isBusy = false
        this.status_pegawai_count = response.data.count
        return response.data.data
      } catch (error) {
        this.isBusy = false
        return []
      }
    },
    async promptDelete(item) {
      Swal.fire({
        title: 'Apakah Anda yakin hendak menghapus ' + item.status_kepegawaian + '?',
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(`/status-pegawai/${item.status_kepegawaian_id}`)
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