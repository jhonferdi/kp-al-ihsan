<template>
  <b-modal id="modal-riwayat-mcu" ref="modal-riwayat-mcu" title="Tambah Riwayat MCU">
    <template #modal-header="{}">
      <h5>Informasi</h5>
    </template>
    <template #default="{}">
      <b-row class="my-1">
        <b-col sm="12">
          <label>Tahun</label>
        </b-col>
        <b-col sm="12">
          <b-form-input type="number" class="mb-3" v-model="form_riwayat_mcu.tahun_mcu"
            :state="getErrorState('tahun_mcu')"></b-form-input>
          <p style="color: red" v-if="getErrorState('tahun_mcu') === false">
            {{ getErrorMessage("tahun_mcu") }}
          </p>
        </b-col>
        <b-col sm="12">
          <label>Jenis Pemeriksaan</label>
        </b-col>
        <b-col sm="12">
          <b-form-input type="text" class="mb-3" v-model="form_riwayat_mcu.jenis_pemeriksaan"
            :state="getErrorState('jenis_pemeriksaan')"></b-form-input>
          <p style="color: red" v-if="getErrorState('jenis_pemeriksaan') === false">
            {{ getErrorMessage("jenis_pemeriksaan") }}
          </p>
        </b-col>
      </b-row>
      <b-row class="my-1" v-for="masterDokumenDigital in masterDokumenDigitals"
        :key="'masterDokumenDigitals-' + masterDokumenDigital.id">
        <b-col sm="12">
          <label>Upload {{ masterDokumenDigital.file_nama }}</label>
        </b-col>
        <b-col sm="12">
          <UploadFile :pegawai="pegawai" :dokumenDigital="dokumenDigital" relationTo="riwayat_mcu"
            :entityId="form_riwayat_mcu.id ?? ''" :masterDokumenDigital="masterDokumenDigital"
            @onUploading="loading = 2" @onUploaded="loading = false" :canEdit="true"
            v-model="form_riwayat_mcu.files[masterDokumenDigital.id]">
          </UploadFile>
        </b-col>
      </b-row>
    </template>
    <template #modal-footer="{}">
      <!-- Emulate built in modal footer ok and cancel button actions -->
      <b-button size="md" variant="info" @click="saveRiwayatMcu()" :disabled="loading">
        <i class="fa fa-save"></i>
        {{
            loading === 2
              ? "Sedang mengunggah"
              : loading
                ? "Sedang Menyimpan"
                : "Simpan"
        }}
      </b-button>
      <b-button size="md" variant="light" @click="cancelRiwayatMcu()">
        Batal
      </b-button>
    </template>
  </b-modal>
</template>

<script>
import Swal from "sweetalert2";
import axios from "axios";
import UploadFile from "./UploadFile.vue";

export default {
  props: ["pegawai", "masterDokumenDigitals", "dokumenDigital"],
  components: { UploadFile },
  data() {
    return {
      form_riwayat_mcu: null,
      loading: false,
      errors: {},
    };
  },
  computed: {
    uploadLoading() {
      for (let i = 0; i < this.masterDokumenDigitals.length; i++) {
        if (this.masterDokumenDigitals[i].loading) {
          return true;
        }
      }
      return false;
    },
  },
  methods: {
    openRiwayatMcuModal(data) {
      if (data) {
        this.form_riwayat_mcu = {
          id: data.id,
          peg_id: data.peg_id,
          tahun_mcu: data.tahun_mcu,
          jenis_pemeriksaan: data.jenis_pemeriksaan,
          files: {},
        };
      } else {
        this.form_riwayat_mcu = {
          peg_id: this.pegawai.peg_id,
          files: {},
        };
      }
      this.errors = {};
      this.$refs["modal-riwayat-mcu"].show();
    },
    async saveRiwayatMcu() {
      this.loading = true;
      try {
        if (this.form_riwayat_mcu.id) {
          let resp = (
            await axios.patch(
              "pegawai/riwayat-mcu/" + this.form_riwayat_mcu.id,
              this.form_riwayat_mcu
            )
          ).data;
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: resp.message,
            confirmButtonText: "ok",
          });
        } else {
          let resp = (
            await axios.post("pegawai/riwayat-mcu", this.form_riwayat_mcu)
          ).data;
          Swal.fire({
            icon: "success",
            title: "Berhasil",
            text: resp.message,
            confirmButtonText: "ok",
          });
        }
        this.loading = false;
        this.$emit("onSave");
        this.$refs["modal-riwayat-mcu"].hide();
      } catch (err) {
        if (err.response && err.response.status == 422) {
          this.errors = err.response.data.errors;
        }
        this.loading = false;
      }
    },
    cancelRiwayatMcu() {
      this.$refs["modal-riwayat-mcu"].hide();
    },
    getErrorState(key) {
      if (this.errors[key]) {
        return false;
      }
      return null;
    },
    getErrorMessage(key) {
      if (this.errors[key]) {
        return this.errors[key].join(", ");
      }
      return null;
    },
  },
};
</script>

<style>

</style>
