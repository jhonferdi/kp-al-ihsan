<template>
  <div>
    <PageHeaderV2 :title="`${$metaInfo.title}`"
      :description="'Daftar seluruh history aktifitas di lingkungan rumah sakit Al Ihsan Provinsi Jawa Barat'" :items="[
        { text: $metaInfo.title, active: true },
      ]" />
    <div class="card -mx-content border-top mb-0 p-2" style="border-color:rgba(224, 224, 224, 1) !important">
      <div class="card-body">
        <div class="d-sm-inline-flex mb-3">
          <b-input-group class="mb-2 mb-sm-0">
            <b-form-input ref="search" @keyup.enter="updateSearch()" :value="ctx.filter" placeholder="Cari aktifitas">
            </b-form-input>
            <b-input-group-append>
              <b-button variant="darkgreen" @click="updateSearch()">
                <b-icon icon="search" />
              </b-button>
            </b-input-group-append>
          </b-input-group>
        </div>
        <b-table class="table-rounded" striped hover responsive head-variant="darkgreen" id="action-table" ref="table"
          :items="itemsProvider" :current-page="ctx.currentPage" :per-page="ctx.perPage" :filter="ctx.filter"
          :busy="isBusy" :sortBy="ctx.sortBy" :sortDesc="ctx.sortDesc" :fields="fields" show-empty
          :empty-filtered-text="`Tidak ada history aktifitas '${ctx.filter}'`">
          <template #cell(index)="data">
            {{ data.index + ctx.perPage * (ctx.currentPage - 1) + 1 }}
          </template>
          <template #cell(user)="row">
            <span>{{ row.item.username || '-' }}</span>
          </template>
          <template #cell(nip)="row">
            <span>{{ row.item.pegawai != null ? row.item.pegawai.peg_nip_nipt_nik : '-' }} -
              {{ row.item.pegawai != null ? row.item.pegawai.peg_nama_tanpa_gelar : '-' }}
            </span>
          </template>
          <template #cell(created_at)="row">
            <span>{{ row.item.created_at }}</span>
          </template>
          <template #cell(aksi)="row">
            <span>{{ row.item.aksi || '-' }}</span>
          </template>
          <template #cell(ket)="row">
            <p>{{ row.item.keterangan || '-' }}</p>
          </template>
          <template #cell(entity)="row">
            <span>{{ row.item.entity_id || '-' }}</span>
          </template>
        </b-table>
        <TablePagination v-model="ctx.currentPage" :itemsLength="action_count" @perPage="getPerPage" />
        <!-- <b-row>
          <b-col sm="6">
            <b-pagination v-model="ctx.currentPage" :total-rows="action_count" :per-page="ctx.perPage"
              aria-controls="action-table">
            </b-pagination>
          </b-col>
          <b-col sm="6" class="d-flex justify-content-end">
            <span class="font-size">Total Data : {{ action_count }}</span>
          </b-col>
        </b-row> -->
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { buildQueryParams } from '~/plugins/utils'

export default {
  //   middleware: ['auth', 'check-permission'],
  middleware: ['auth'],


  head() {
    return { title: 'History Aktifitas' }
  },

  async asyncData() {
    if (window.tablectxaction) {
      var ctx = window.tablectxaction
    } else {
      var ctx = {
        currentPage: 1,
        perPage: 20,
        filter: '',
        sortBy: 'created_at',
        sortDesc: true
      }
    }

    let f1resp = (await axios.get('/action-log?' + buildQueryParams(ctx))).data
    let action = f1resp.data
    let action_count = f1resp.count

    return {
      action,
      action_count,
      ctx,
    }
  },

  data: () => ({
    fields: [
      { key: 'index', thStyle: { width: "2%" }, label: 'No', sortable: false, sortDirection: 'asc', thClass: 'bg-darkgreen text-white' },
      { key: 'user', thStyle: { width: "10%" }, label: 'Username', sortable: false, sortDirection: 'asc', thClass: 'bg-darkgreen text-white' },
      { key: 'nip', thStyle: { width: "10%" }, label: 'Nip & Nama', sortable: false, sortDirection: 'asc', thClass: 'bg-darkgreen text-white' },
      { key: 'created_at', thStyle: { width: "10%" }, label: 'Waktu', sortable: true, sortDirection: 'asc', thClass: 'bg-darkgreen text-white' },
      { key: 'aksi', thStyle: { width: "10%" }, label: 'Aktifitas', sortable: false, sortDirection: 'asc', thClass: 'bg-darkgreen text-white' },
      { key: 'ket', thStyle: { width: "20%" }, label: 'Keterangan', sortable: false, sortDirection: 'asc', thClass: 'bg-darkgreen text-white' },
      { key: 'entity', thStyle: { width: "10%" }, label: 'Entity', sortable: false, sortDirection: 'asc', thClass: 'bg-darkgreen text-white' }
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
        return this.action
      }

      ctx.params = this.ctx.params
      this.isBusy = true

      try {
        window.tablectxaction = ctx
        const response = await axios.get('/action-log?' + buildQueryParams(ctx))
        this.isBusy = false
        this.action_count = response.data.count
        return response.data.data
      } catch (error) {
        this.isBusy = false
        return []
      }
    },
    refreshTable() {
      this.$refs.table.refresh()
    },
    getPerPage(count) {
      this.ctx.perPage = count;
    },
    updateSearch() {
      this.ctx.filter = this.$refs.search.$el.value;
      this.ctx.currentPage = 1;
    },
  }
}
</script>
<style>
.font-size {
  font-size: 1rem;
}
</style>
