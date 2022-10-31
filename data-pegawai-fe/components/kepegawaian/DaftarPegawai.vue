<template>
    <div>
        <div class="d-sm-inline-flex mb-3">
            <div class="d-sm-inline-flex">
                <b-input-group class="mb-2 mb-sm-0" style="max-width:200px">
                    <b-form-input ref="search" @keyup.enter="updateSearch()" :value="ctx.filter"
                        placeholder="Cari pegawai"></b-form-input>
                    <b-input-group-append>
                        <b-button variant="darkgreen" @click="updateSearch()">
                            <b-icon icon="search" />
                        </b-button>
                    </b-input-group-append>
                </b-input-group>
                <div class="ml-sm-3 d-inline-block" :style="[{ width: roleOptions.length > 0 ? '200px' : 'auto' }]"
                    style="min-height:36.53px; max-width:100%">
                    <v-select v-if="roleOptions.length > 0" :options="roleOptions"
                        :reduce="item => item.status_kepegawaian_id" label="status_kepegawaian"
                        placeholder="Semua Status Pegawai" v-model="ctx.params.status_kepegawaian_id"
                        style="width: 100%;">
                    </v-select>
                </div>
            </div>
            <div class="mx-3 d-none d-sm-block border-left" style="border-color:#E0E0E0 !important"></div>
            <!-- <b-button variant="darkgreen" :to="{ name: 'pegawai.create' }" size="sm"
                class="text-nowrap mr-2 px-3 d-inline-flex align-items-center">Tambah Pegawai</b-button> -->
            <b-button variant="darkgreen" @click="openModalTambah()" size="sm"
                class="text-nowrap mr-2 px-3 d-inline-flex align-items-center">Tambah Pegawai</b-button>
            <ModalPegawai :open="modalPegawaiOpen" pegawai:pegawaiData size="xs" :title="`${title} Pegawai`"
                @close="modalPegawaiOpen = false" @onSave="refreshData" />
        </div>
        <b-table class="table-rounded" striped hover responsive head-variant="darkgreen" id="pegawai-table" ref="table"
            :items="itemsProvider" :current-page="ctx.currentPage" :per-page="ctx.perPage" :filter="ctx.filter"
            :busy="isBusy" :sortBy="ctx.sortBy" :sortDesc="ctx.sortDesc" :fields="fields" show-empty
            :empty-text="'Tidak ada pegawai ditemukan'"
            :empty-filtered-text="`Tidak ada pegawai bernama '${ctx.filter}'`">
            <template #cell(index)="data">
                {{ data.index + ctx.perPage * (ctx.currentPage - 1) + 1 }}
            </template>

            <template #cell(peg_nama_lengkap)="row">
                <router-link class="text-primary" style="text-decoration:underline !important"
                    :to="'/pegawai/' + row.item.peg_id">{{ row.item.peg_nama_lengkap || '-' }}</router-link>
            </template>
            <template #cell(jb)="row">
                <span>{{ row.item.jabatan ? row.item.jabatan.jabatan_nama : '' }}</span>
            </template>
            <template #cell(sk)="row">
                <span>{{ row.item.status_pegawai ? row.item.status_pegawai.status_kepegawaian : '' }}</span>
            </template>
            <template #cell(peg_nip_nipt_nik)="row">
                <span>{{ row.item.peg_nip_nipt_nik || '-' }}</span>
            </template>
            <template #cell(actions)="row">
                <div class="text-nowrap">
                    <nuxt-link :to="{ name: 'pegawai.view', params: { id: row.item.peg_id } }"
                        class="text-darkblue p-1 d-inline-flex align-items-center bg-softblue rounded">
                        <i class="mb-0 bx bx-edit-alt" style="font-size:18px"></i>
                    </nuxt-link>
                    <!-- <a href="#" @click.prevent="openModalEdit(row.item)"
                        class="text-darkblue p-1 d-inline-flex align-items-center bg-softblue rounded">
                        <i class="mb-0 bx bx-edit-alt" style="font-size:18px"></i>
                    </a> -->
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
        <modal-hapus name="Pegawai" :open="modalHapusOpen" @close="modalHapusOpen = false" @delete="deleteItem" />
        <TablePagination v-model="ctx.currentPage" :itemsLength="pegawai_count" @perPage="getPerPage" />
    </div>
</template>
<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { mapGetters } from "vuex";
import { buildQueryParams } from '~/plugins/utils'
import ModalPegawai from '../modals/ModalPegawai.vue'

export default {
    middleware: ["auth"],
    data: () => ({
        ctx: {},
        pegawai: null,
        pegawai_count: null,
        roleOptions: [],
        fields: [
            { key: "index", label: "No", thClass: 'bg-darkgreen text-white' },
            { key: "peg_nama_lengkap", label: "Nama Lengkap", sortable: true, sortDirection: "asc", thClass: 'bg-darkgreen text-white' },
            { key: "jb", label: "Jabatan", sortable: false, sortDirection: "asc", thClass: 'bg-darkgreen text-white' },
            { key: "sk", label: "Status Pegawai", sortable: false, sortDirection: "asc", thClass: 'bg-darkgreen text-white' },
            { key: "peg_nip_nipt_nik", label: "NIP/NIPT/NIK", sortable: false, sortDirection: "asc", thClass: 'bg-darkgreen text-white' },
            { key: "actions", label: "Aksi", thClass: 'bg-darkgreen text-white' }
        ],
        isTableInit: false,
        isBusy: false,
        selected: [],
        modalPegawaiOpen: false,
        modalHapusOpen: false,
        onDeleteItem: null,
        pegawaiData: null,
        title: null,
    }),
    mounted() {
        this.init();
        this.$store.dispatch("useroptions/fetchOptions");
    },
    watch: {
        "ctx.params.status_kepegawaian_id"() {
            this.refreshTable();
        }
    },
    // computed: {
    //   selectedItems: function(){
    //     return this.pegawai.filter(function(ctx){
    //     })
    //   }
    // },
    methods: {
        async init() {
            try {
                if (window.tablectxpegawai) {
                    this.ctx = window.tablectxpegawai;
                }
                else {
                    this.ctx = {
                        currentPage: 1,
                        perPage: 20,
                        filter: "",
                        sortBy: "peg_nama_lengkap",
                        sortDesc: false,
                        params: {
                            status_kepegawaian_id: ""
                        }
                    };
                }
                let f1resp = (await axios.get("/pegawai?" + buildQueryParams(this.ctx))).data;
                let f2resp = (await axios.get("/status-pegawai")).data;
                this.pegawai = f1resp.data;
                this.pegawai_count = f1resp.count;
                this.roleOptions = f2resp.data;
                this.roleOptions = [...this.roleOptions, { id: 0, name: "Belum diset" }];
            }
            catch (e) {
                this.$nuxt.error({
                    statusCode: 403,
                    message: "Akses Terbatas"
                });
            }
        },
        async itemsProvider(ctx) {
            if (!this.isTableInit) {
                this.isTableInit = true;
                return this.pegawai;
            }
            ctx.params = this.ctx.params;
            this.isBusy = true;
            try {
                window.tablectxpegawai = ctx;
                const response = await axios.get("/pegawai?" + buildQueryParams(ctx));
                this.isBusy = false;
                this.pegawai_count = response.data.count;
                return response.data.data;
            }
            catch (error) {
                this.isBusy = false;
                return [];
            }
        },
        async promptDelete(item) {
            Swal.fire({
                title: "Apakah Anda yakin hendak menghapus " + item.peg_nama_lengkap + "?",
                showDenyButton: true,
                confirmButtonText: `Hapus`,
                denyButtonText: `Batal`,
            }).then(async (result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    const response = await axios.delete(`/pegawai/${item.peg_id}`);
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
                const response = await axios.delete(`/pegawai/${this.onDeleteItem.peg_id}`);
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
        openModalEdit(pegawaiData) {
            this.modalPegawaiOpen = true
            this.pegawaiData = pegawaiData
            this.title = 'Ubah'
        },
        openModalTambah() {
            this.modalPegawaiOpen = true
            this.pegawaiData = null
            this.title = 'Tambah'
        },
        async refreshData() {
            this.refreshTable()
        },
    },
    components: { ModalPegawai }
}
</script>
<style>
.font-size {
    font-size: 1rem;
}
</style>
