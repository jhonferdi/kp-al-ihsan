<template>
    <div>
        <div class="d-sm-inline-flex mb-3">
            <div class="d-sm-inline-flex">
                <b-input-group class="mb-2 mb-sm-0 d-flex" style="max-width:230px">
                    <b-form-input ref="search" @keyup.enter="updateSearch()" :value="ctx.filter"
                        placeholder="Cari status pegawai"></b-form-input>
                    <b-input-group-append>
                        <span>
                            <b-button variant="darkgreen" @click="updateSearch()">
                                <b-icon icon="search" />
                            </b-button>
                        </span>
                    </b-input-group-append>
                </b-input-group>
            </div>
            <div class="mx-3 d-none d-sm-block border-left" style="border-color:#E0E0E0 !important"></div>
            <!-- <b-button variant="darkgreen" :to="{ name: 'status-pegawai.create' }" size="sm"
                class="text-nowrap mr-2 px-3 d-inline-flex align-items-center">Tambah Status Pegawai</b-button> -->
            <b-button variant="darkgreen" @click="openModalTambah()" size="sm"
                class="text-nowrap mr-2 px-3 d-inline-flex align-items-center">Tambah Status Pegawai</b-button>
            <ModalStatusKepegawaian :open="modalStatusPegawaiOpen" :statusPegawai="statusPegawaiData" size="xs"
                :title="`${title} Status Kepegawaian`" @close="modalStatusPegawaiOpen = false" @onSave="refreshData" />
        </div>
        <b-table class="table-rounded" striped hover responsive head-variant="darkgreen" id="status-pegawai-table"
            ref="table" :items="itemsProvider" :current-page="ctx.currentPage" :per-page="ctx.perPage"
            :filter="ctx.filter" :busy="isBusy" :sortBy="ctx.sortBy" :sortDesc="ctx.sortDesc" :fields="fields"
            show-empty :empty-text="'Tidak ada status kepegawaian ditemukan'"
            :empty-filtered-text="`Tidak ada status kepegawaian bernama '${ctx.filter}'`">
            <template #cell(index)="data">
                {{ data.index + ctx.perPage * (ctx.currentPage - 1) + 1 }}
            </template>

            <template #cell(status_kepegawaian)="row">
                <span>{{ row.item.status_kepegawaian || '-' }}</span>
            </template>
            <template #cell(actions)="row">
                <div class="text-nowrap">
                    <!-- <nuxt-link :to="{ name: 'pegawai-ruangan.edit', params: { id: row.item.peg_ruangan_id } }"
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
        <modal-hapus name="status kepegawaian" :open="modalHapusOpen" @close="modalHapusOpen = false"
            @delete="deleteItem" />
        <TablePagination v-model="ctx.currentPage" :itemsLength="status_pegawai_count" @perPage="getPerPage" />
    </div>
</template>
<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { buildQueryParams } from '~/plugins/utils'
import ModalStatusKepegawaian from '../modals/ModalStatusKepegawaian.vue'

export default {
    middleware: ["auth"],
    data: () => ({
        ctx: {},
        status_pegawai: null,
        status_pegawai_count: null,
        ruangOptions: [],
        fields: [
            { key: "index", label: "No", thClass: 'bg-darkgreen text-white' },
            { key: 'status_kepegawaian', tdClass: 'w-100', label: 'Nama Status Kepegawaian', sortable: true, sortDirection: 'asc', thClass: 'bg-darkgreen text-white' },
            { key: "actions", label: "Aksi", thClass: 'bg-darkgreen text-white' }
        ],
        isTableInit: false,
        isBusy: false,
        modalStatusPegawaiOpen: false,
        modalHapusOpen: false,
        onDeleteItem: null,
        statusPegawaiData: null,
        title: null,
    }),
    mounted: function () {
        this.init();
    },
    watch: {
    },
    // computed: {
    //   selectedItems: function(){
    //     return this.pegawai.filter(function(ctx){
    //     })
    //   }
    // },
    methods: {
        async init() {
            if (window.tablectxstatuspeg) {
                this.ctx = window.tablectxstatuspeg
            } else {
                this.ctx = {
                    currentPage: 1,
                    perPage: 20,
                    filter: '',
                    sortBy: 'status_kepegawaian',
                    sortDesc: false
                }
            }

            let f1resp = (await axios.get('/status-pegawai?' + buildQueryParams(this.ctx))).data
            this.status_pegawai = f1resp.data
            this.status_pegawai_count = f1resp.count

        },
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
        async deleteItem() {
            if (this.onDeleteItem) {
                const response = await axios.delete(`/status-pegawai/${this.onDeleteItem.status_kepegawaian_id}`)
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
        showHapusModal(item) {
            this.onDeleteItem = item;
            this.modalHapusOpen = true;
        },
        openModalEdit(statusPegawaiData) {
            this.modalStatusPegawaiOpen = true
            this.statusPegawaiData = statusPegawaiData
            this.title = 'Ubah'
        },
        openModalTambah() {
            this.modalStatusPegawaiOpen = true
            this.statusPegawaiData = null
            this.title = 'Tambah'
        },
        async refreshData() {
            this.refreshTable()
        },
    },
    components: { ModalStatusKepegawaian }
}
</script>
