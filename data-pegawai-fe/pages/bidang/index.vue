<template>
  <div>

    <PageHeaderV2 :title="`${$metaInfo.title}`"
      :description="'Daftar seluruh bidang pekerjaan di lingkungan rumah sakit Al Ihsan Provinsi Jawa Barat'" :items="[
        { text: $metaInfo.title, active: true },
      ]" :imageUrl="'bidang.svg'" />
    <div class="card -mx-content border-top mb-0 p-2" style="border-color:rgba(224, 224, 224, 1) !important">
      <div class="card-body">
        <!-- <div class="d-flex justify-content-end mb-4">
          <div>
            <b-button variant="outline-success" :to="{ name: 'bidang.create' }">
              <b-icon icon="plus"></b-icon> Tambah Bidang
            </b-button>
          </div>
        </div>
        <b-row class="my-1">
          <b-col sm="6">
            <div class="d-flex align-items-center mb-3">
              <div class="mr-4 text-nowrap">Cari Bidang</div>
              <b-form-input class="w-100" v-model="ctx.filter" placeholder="Masukan Nama Bidang">
              </b-form-input>
            </div>
          </b-col>
        </b-row> -->
        <div class="d-sm-inline-flex mb-3">
          <b-input-group class="mb-2 mb-sm-0">
            <b-form-input ref="search" @keyup.enter="updateSearch()" :value="ctx.filter" placeholder="Cari bidang">
            </b-form-input>
            <b-input-group-append>
              <b-button variant="darkgreen" @click="updateSearch()">
                <b-icon icon="search" />
              </b-button>
            </b-input-group-append>
          </b-input-group>
          <div class="mx-3 d-none d-sm-block border-left" style="border-color:#E0E0E0 !important"></div>
          <!-- <b-button variant="darkgreen" :to="{ name: 'bidang.create' }" size="sm"
            class="text-nowrap mr-2 px-3 d-inline-flex align-items-center">Tambah Bidang</b-button> -->
          <b-button variant="darkgreen" @click="openModalTambah()" size="sm"
            class="text-nowrap mr-2 px-3 d-inline-flex align-items-center">Tambah Bidang</b-button>
          <ModalBidang :open="modalBidangOpen" :bidang="bidangData" size="xs" :title="`${title} Bidang`"
            @close="modalBidangOpen = false" @onSave="refreshData" />
        </div>
        <b-table class="table-rounded" striped hover head-variant="darkgreen" id="bidang-table" ref="table"
          :items="itemsProvider" :current-page="ctx.currentPage" :per-page="ctx.perPage" :filter="ctx.filter"
          :busy="isBusy" :sortBy="ctx.sortBy" :sortDesc="ctx.sortDesc" :fields="fields" show-empty
          :empty-filtered-text="`Tidak ada bidang bernama '${ctx.filter}'`">
          <template #cell(index)="data">
            {{ data.index + ctx.perPage * (ctx.currentPage - 1) + 1 }}
          </template>
          <template #cell(bidang_nama)="row">
            <span>{{ row.item.bidang_nama || '-' }}</span>
          </template>
          <template #cell(actions)="row">
            <div class="text-nowrap">
              <!-- <nuxt-link :to="{ name: 'bidang.edit', params: { id: row.item.bidang_id } }"
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
                @click="onDeleteItem = row.item; modalHapusOpen = true ">
                <i class="mb-0 bx bx-trash" style="font-size:18px"></i>
              </b-link>
            </div>
          </template>
        </b-table>
        <modal-hapus name="bidang" :open="modalHapusOpen" @close="modalHapusOpen = false" @delete="deleteItem" />
        <TablePagination v-model="ctx.currentPage" :itemsLength="bidang_count" @perPage="getPerPage" />
        <!-- <b-row>
          <b-col sm="6">
            <b-pagination v-model="ctx.currentPage" :total-rows="bidang_count" :per-page="ctx.perPage"
              aria-controls="bidang-table">
            </b-pagination>
          </b-col>
          <b-col sm="6" class="d-flex justify-content-end">
            <span class="font-size">Total Data : {{ bidang_count }}</span>
          </b-col>
        </b-row> -->
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { buildQueryParams } from '~/plugins/utils'
import ModalBidang from '~/components/modals/ModalBidang.vue'

export default {
  //   middleware: ['auth', 'check-permission'],
  middleware: ["auth"],
  head() {
    return { title: "Bidang" };
  },
  async asyncData() {
    if (window.tablectxbidang) {
      var ctx = window.tablectxbidang;
    }
    else {
      var ctx = {
        currentPage: 1,
        perPage: 20,
        filter: "",
        sortBy: "bidang_nama",
        sortDesc: false
      };
    }
    let f1resp = (await axios.get("/bidang?" + buildQueryParams(ctx))).data;
    let bidang = f1resp.data;
    let bidang_count = f1resp.count;
    return {
      bidang,
      bidang_count,
      ctx,
    };
  },
  data: () => ({
    fields: [
      { key: "index", label: "No", thClass: 'bg-darkgreen text-white' },
      { key: "bidang_nama", label: "Nama Bidang", tdClass: "w-100", thClass: 'bg-darkgreen text-white',  sortable: true, sortDirection: "asc" },
      { key: "actions", label: "Aksi", thClass: 'bg-darkgreen text-white' }
    ],
    isTableInit: false,
    isBusy: false,
    modalBidangOpen: false,
    modalHapusOpen: false,
    onDeleteItem: null,
    bidangData: null,
    title: null
  }),
  created: function () {
  },
  watch: {},
  methods: {
    async itemsProvider(ctx) {
      if (!this.isTableInit) {
        this.isTableInit = true;
        return this.bidang;
      }
      ctx.params = this.ctx.params;
      this.isBusy = true;
      try {
        window.tablectxbidang = ctx;
        const response = await axios.get("/bidang?" + buildQueryParams(ctx));
        this.isBusy = false;
        this.bidang_count = response.data.count;
        return response.data.data;
      }
      catch (error) {
        this.isBusy = false;
        return [];
      }
    },
    async promptDelete(item) {
      Swal.fire({
        title: "Apakah Anda yakin hendak menghapus " + item.bidang_nama + "?",
        showDenyButton: true,
        confirmButtonText: `Hapus`,
        denyButtonText: `Batal`,
      }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
          const response = await axios.delete(`/bidang/${item.bidang_id}`);
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
    async deleteItem() {
      if (this.onDeleteItem) {
        const response = await axios.delete(`/bidang/${this.onDeleteItem.bidang_id}`);
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
    openModalEdit(bidangData) {
      this.modalBidangOpen = true
      this.bidangData = bidangData
      this.title = 'Ubah'
    },
    openModalTambah() {
      this.modalBidangOpen = true
      this.bidangData = null
      this.title = 'Tambah'
    },
    async refreshData() {
      this.refreshTable()
    }
  },
  components: { ModalBidang }
}
</script>
<style>
.font-size {
  font-size: 1rem;
}
</style>
