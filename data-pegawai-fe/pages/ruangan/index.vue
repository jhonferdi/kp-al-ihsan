<template>
  <div>
    <PageHeaderV2 :title="`${$metaInfo.title}`"
      :description="'Daftar seluruh ruangan di lingkungan rumah sakit Al Ihsan Provinsi Jawa Barat'" :items="[
        { text: $metaInfo.title, active: true },
      ]" :imageUrl="'ruangan.svg'" />
    <div class="card -mx-content border-top mb-0 p-2" style="border-color:rgba(224, 224, 224, 1) !important">
      <div class="card-body">
        <div class="d-sm-inline-flex mb-3">
          <b-input-group class="mb-2 mb-sm-0">
            <b-form-input ref="search" @keyup.enter="updateSearch()" :value="ctx.filter" placeholder="Cari Ruangan">
            </b-form-input>
            <b-input-group-append>
              <b-button variant="darkgreen" @click="updateSearch()">
                <b-icon icon="search" />
              </b-button>
            </b-input-group-append>
          </b-input-group>
          <div class="mx-3 d-none d-sm-block border-left" style="border-color:#E0E0E0 !important"></div>
          <!-- <b-button variant="darkgreen" :to="{ name: 'ruangan.create' }" size="sm"
            class="text-nowrap mr-2 px-3 d-inline-flex align-items-center">Tambah Ruangan</b-button> -->
          <b-button variant="darkgreen" @click="openModalTambah()" size="sm"
            class="text-nowrap mr-2 px-3 d-inline-flex align-items-center">Tambah Ruangan</b-button>
          <ModalRuangan :open="modalRuanganOpen" :ruangan="ruanganData" size="xs" :title="`${title} Ruangan`"
            @close="modalRuanganOpen = false" @onSave="refreshData" />
        </div>
        <b-table class="table-rounded" striped hover head-variant="darkgreen" id="ruangan-table" ref="table"
          :items="itemsProvider" :current-page="ctx.currentPage" :per-page="ctx.perPage" :filter="ctx.filter"
          :busy="isBusy" :sortBy="ctx.sortBy" :sortDesc="ctx.sortDesc" :fields="fields" show-empty
          :empty-filtered-text="`Tidak ada Ruangan bernama '${ctx.filter}'`">
          <template #cell(index)="data">
            {{ data.index + ctx.perPage * (ctx.currentPage - 1) + 1 }}
          </template>
          <template #cell(nama_ruangan)="row">
            <span>{{ row.item.nama_ruangan || '-' }}</span>
          </template>
          <template #cell(uk)="row">
            <span>{{ row.item.unit_kerja ? row.item.unit_kerja.unit_kerja_nama : '' }}</span>
          </template>
          <template #cell(actions)="row">
            <div class="text-nowrap">
              <!-- <nuxt-link :to="{ name: 'ruangan.edit', params: { id: row.item.ruangan_id } }"
                class="text-darkblue p-1 d-inline-flex align-items-center bg-softblue rounded">
                <i class="mb-0 bx bx-edit-alt" style="font-size:18px"></i>
              </nuxt-link> -->
              <a href="#" @click.prevent="openModalEdit(row.item)"
                class="text-darkblue p-1 d-inline-flex align-items-center bg-softblue rounded">
                <i class="mb-0 bx bx-edit-alt" style="font-size:18px"></i>
              </a>
              <!-- <b-link size="sm" class="text-danger p-1 d-inline-flex align-items-center bg-pink-new rounded" @click="promptDelete(row.item)">
                    <i class="mb-0 bx bx-trash" style="font-size:18px"></i>
                  </b-link> -->
              <b-link size="sm" class="text-danger p-1 d-inline-flex align-items-center bg-pink-new rounded"
                @click="onDeleteItem = row.item; modalHapusOpen = true">
                <i class="mb-0 bx bx-trash" style="font-size:18px"></i>
              </b-link>
            </div>
          </template>
        </b-table>
        <modal-hapus name="Ruangan" :open="modalHapusOpen" @close="modalHapusOpen = false" @delete="deleteItem" />
        <TablePagination v-model="ctx.currentPage" :itemsLength="ruangan_count" @perPage="getPerPage" />
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { buildQueryParams } from '~/plugins/utils'
import ModalRuangan from '~/components/modals/ModalRuangan.vue'

export default {
  //   middleware: ['auth', 'check-permission'],
  middleware: ["auth"],
  head() {
    return { title: "Ruangan" };
  },
  async asyncData() {
    if (window.tablectxruangan) {
      var ctx = window.tablectxruangan
    } else {
      var ctx = {
        currentPage: 1,
        perPage: 20,
        filter: '',
        sortBy: 'nama_ruangan',
        sortDesc: false
      }
    }

    let f1resp = (await axios.get('/ruangan?' + buildQueryParams(ctx))).data
    let ruangan = f1resp.data
    let ruangan_count = f1resp.count

    return {
      ruangan,
      ruangan_count,
      ctx,
    }
  },
  data: () => ({
    fields: [
      { key: "index", label: "No", thClass: 'bg-darkgreen text-white' },
      { key: 'nama_ruangan', label: 'Nama Ruangan', tdClass: "w-50", sortable: true, sortDirection: 'asc', thClass: 'bg-darkgreen text-white' },
      { key: 'uk', label: 'Unit Kerja Fungsional', tdClass: "w-50", sortable: false, sortDirection: 'asc', thClass: 'bg-darkgreen text-white' },
      { key: "actions", label: "Aksi", thClass: 'bg-darkgreen text-white' }
    ],
    isTableInit: false,
    isBusy: false,
    modalRuanganOpen: false,
    modalHapusOpen: false,
    onDeleteItem: null,
    ruanganData:null,
    title:null
  }),
  created: function () {
  },
  watch: {},
  methods: {
    async itemsProvider(ctx) {
      if (!this.isTableInit) {
        this.isTableInit = true
        return this.ruangan
      }

      ctx.params = this.ctx.params
      this.isBusy = true

      try {
        window.tablectxruangan = ctx
        const response = await axios.get('/ruangan?' + buildQueryParams(ctx))
        this.isBusy = false
        this.ruangan_count = response.data.count
        return response.data.data
      } catch (error) {
        this.isBusy = false
        return []
      }
    },
    async promptDelete(item) {
      Swal.fire({
        title: "Apakah Anda yakin hendak menghapus " + item.nama_ruangan + "?",
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(`/ruangan/${item.ruangan_id}`);
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "Data berhasil dihapus",
            confirmButtonText: "Ok",
          });
          this.$refs.table.refresh();
        }
      });
    },
    showHapusModal(item) {
      this.onDeleteItem = item;
      this.modalHapusOpen = true
    },
    async deleteItem() {
      if (this.onDeleteItem) {
        const response = await axios.delete(`/ruangan/${this.onDeleteItem.ruangan_id}`);
        this.modalHapusOpen = false;
        Swal.fire({
          icon: "success",
          title: "Berhasil",
          text: "Data berhasil dihapus",
          confirmButtonText: "Ok",
        });
        this.$refs.table.refresh();
      }
    },
    refreshTable() {
      this.$refs.table.refresh();
    },
    getPerPage(count) {
      this.ctx.perPage = count;
    },
    updateSearch() {
      this.ctx.filter = this.$refs.search.$el.value;
      this.ctx.currentPage = 1;
    },
    openModalEdit(ruanganData) {
      this.modalRuanganOpen = true
      this.ruanganData = ruanganData
      this.title = 'Ubah'
    },
    openModalTambah() {
      this.modalRuanganOpen = true
      this.ruanganData = null
      this.title = 'Tambah'
    },
    async refreshData() {
      this.refreshTable()
    },
  },
  components: { ModalRuangan }
}
</script>
<style>
.font-size {
  font-size: 1rem;
}
</style>
