<template>
  <div>
    <page-header :title="`${$metaInfo.title}`" :items="[
      {text: $metaInfo.title, active: true},
    ]" />
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-end mb-4">
          <div>
            <b-button variant="outline-success" :to="{ name: 'sertifikat.create' }">
              <b-icon icon="plus"></b-icon> Tambah Sertifikat
            </b-button>
          </div>
        </div>
        <b-row class="my-1">
          <b-col sm="6">
            <div class="d-flex align-items-center mb-3">
              <div class="mr-4 text-nowrap">Cari Sertifikat</div>
              <b-form-input class="w-100" v-model="ctx.filter" placeholder="Masukan Judul Sertif / Kategori">
              </b-form-input>
            </div>
          </b-col>
        </b-row>
        <b-table hover responsive id="sertifikat-table" ref="table" :items="itemsProvider"
          :current-page="ctx.currentPage" :per-page="ctx.perPage" :filter="ctx.filter" :busy="isBusy"
          :sortBy="ctx.sortBy" :sortDesc="ctx.sortDesc" :fields="fields">
          <template #cell(pid)="row">
            <span>{{ row.item.pegawai ? row.item.pegawai.peg_nama_lengkap : '' }}</span>
          </template>
          <template #cell(kategori)="row">
            <span>{{ row.item.kategori || '-' }}</span>
          </template>
          <template #cell(judul_sertifikat)="row">
            <span>{{ row.item.judul_sertifikat || '-' }}</span>
          </template>
          <template #cell(tanggal_aktif)="row">
            <span>{{ row.item.tanggal_aktif || '-' }}</span>
          </template>
          <template #cell(image_sertifikat)="row">
            <span>{{ row.item.image_sertifikat || '-' }}</span>
          </template>
          <template #cell(actions)="row">
            <nuxt-link :to="{ name: 'sertifikat.edit', params: { id: row.item.sertifikat_id }}" class="mr-2">
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
            <b-pagination v-model="ctx.currentPage" :total-rows="sertifikat_count" :per-page="ctx.perPage"
              aria-controls="sertifikat-table">
            </b-pagination>
          </b-col>
          <b-col sm="6" class="d-flex justify-content-end">
            <span class="font-size">Total Data : {{ sertifikat_count }}</span>
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
    return { title: 'Sertifikasi' }
  },

  async asyncData() {
    if (window.tablectxsertif) {
      var ctx = window.tablectxsertif
    } else {
      var ctx = {
        currentPage: 1,
        perPage: 20,
        filter: '',
        sortBy: 'judul_sertifikat',
        sortDesc: false
      }
    }

    let f1resp = (await axios.get('/sertifikat?' + buildQueryParams(ctx))).data
    let sertifikat = f1resp.data
    let sertifikat_count = f1resp.count

    return {
      sertifikat,
      sertifikat_count,
      ctx,
    }
  },

  data: () => ({
    fields: [
      { key: 'pid', label: 'Nama Pegawai', sortable: false, sortDirection: 'asc' },
      { key: 'kategori', label: 'Kategori', sortable: false, sortDirection: 'asc' },
      { key: 'judul_sertifikat', label: 'Judul Sertifikat', sortable: false, sortDirection: 'asc' },
      { key: 'tanggal_aktif', label: 'Tanggal Aktif', sortable: false, sortDirection: 'asc' },
      { key: 'image_sertifikat', label: 'Image', sortable: true, sortDirection: 'asc' },
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
        return this.sertifikat
      }

      ctx.params = this.ctx.params
      this.isBusy = true

      try {
        window.tablectxsertif = ctx
        const response = await axios.get('/sertifikat?' + buildQueryParams(ctx))
        this.isBusy = false
        this.sertifikat_count = response.data.count
        return response.data.data
      } catch (error) {
        this.isBusy = false
        return []
      }
    },
    async promptDelete(item) {
      Swal.fire({
        title: 'Apakah Anda yakin hendak menghapus ' + item.judul_sertifikat + '?',
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(`/sertifikat/${item.sertifikat_id}`)
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