<template>
	<modal :open="open" :size="size" @close="$emit('close')" :title="title">
		<FormWizard ref="wizard" sequential hideControls @activestep="setActiveStep">
			<StepItem>
				<div class="border p-3 border-lightgray rounded">
					<h5>Profil</h5>
					<div class="mb-3">
						<label>Nama Tanpa Gelar</label>
						<b-form-input placeholder="Masukkan Tanpa Gelar" v-model="form.peg_nama_tanpa_gelar"
							:state="getErrorState('peg_nama_tanpa_gelar')"></b-form-input>
						<p style="color:red;" v-if="getErrorState('peg_nama_tanpa_gelar') === false">
							{{ getErrorMessage('peg_nama_tanpa_gelar') }}
						</p>
					</div>
					<b-row class="mb-3">
						<b-col cols="6">
							<label>Gelar Depan</label>
							<b-form-input placeholder="Masukkan Gelar Depan, Kosongkan Jika Tidak Ada"
								v-model="form.peg_gelar_depan"></b-form-input>
						</b-col>
						<b-col cols="6">
							<label>Gelar Belakang</label>
							<b-form-input placeholder="Masukkan Gelar Belakang, Kosongkan Jika Tidak Ada"
								v-model="form.peg_gelar_belakang"></b-form-input>
						</b-col>
					</b-row>
					<div class="mb-3">
						<label>Nama Lengkap</label>
						<b-form-input placeholder="Masukkan Nama Lengkap" v-model="fullName"
							:state="getErrorState('peg_nama_lengkap')" disabled></b-form-input>
						<p style="color:red;" v-if="getErrorState('peg_nama_lengkap') === false">
							{{ getErrorMessage('peg_nama_lengkap') }}
						</p>
					</div>
					<div class="mb-3">
						<label>NIP/NIPT/NIK</label>
						<b-form-input placeholder="Masukkan NIP/NIPT/NIK" v-model="form.peg_nip_nipt_nik">
						</b-form-input>
					</div>
					<div class="mb-3">
						<label>Tempat Lahir</label>
						<b-form-input placeholder="Masukkan Tempat Lahir" v-model="form.peg_lahir_tempat"
							:state="getErrorState('peg_lahir_tempat')"></b-form-input>
						<p style="color:red;" v-if="getErrorState('peg_lahir_tempat') === false">
							{{ getErrorMessage('peg_lahir_tempat') }}
						</p>
					</div>
					<div class="mb-3">
						<label>Tanggal Lahir</label>
						<b-form-input type="date" placeholder="Masukkan Tanggal Lahir" v-model="form.peg_lahir_tanggal"
							:state="getErrorState('peg_lahir_tanggal')">
						</b-form-input>
						<p style="color:red;" v-if="getErrorState('peg_lahir_tanggal') === false">
							{{ getErrorMessage('peg_lahir_tanggal') }}
						</p>
					</div>
					<div class="mb-3">
						<label>Jenis Kelamin</label>
						<b-form-select class="font-size-13" v-model="form.peg_jenis_kelamin" :options="JKOptions"
							value-field="value" text-field="text" :state="getErrorState('peg_jenis_kelamin')">
						</b-form-select>
						<p style="color:red;" v-if="getErrorState('peg_jenis_kelamin') === false">
							{{ getErrorMessage('peg_jenis_kelamin') }}
						</p>
					</div>
					<div class="mb-3">
						<label>Agama</label>
						<b-form-select class="font-size-13" v-model="form.peg_agama" :options="AOptions"
							value-field="value" text-field="text">
						</b-form-select>
					</div>
					<div class="mb-3">
						<label>Golongan Darah</label>
						<b-form-select class="font-size-13" v-model="form.peg_golongan_darah" :options="GOptions"
							value-field="value" text-field="text">
						</b-form-select>
					</div>
					<div class="mb-3">
						<label>Status Pernikahan</label>
						<b-form-select class="font-size-13" v-model="form.peg_status_pernikahan" :options="SPOptions"
							value-field="value" text-field="text">
						</b-form-select>
					</div>
					<div class="mb-3">
						<label>Tingkat Pendidikan</label>
						<b-form-select class="font-size-13" v-model="form.kualifikasi_pendidikan_id"
							:options="KPOptions" value-field="kualifikasi_pendidikan_id"
							text-field="kualifikasi_pendidikan">
						</b-form-select>
					</div>
					<div class="mb-3">
						<label>Jurusan</label>
						<b-form-select class="font-size-13" v-model="form.pendidikan_id" :options="PDOptions"
							value-field="pendidikan_id" text-field="pendidikan_nama">
						</b-form-select>
					</div>
					<div class="mb-0">
						<label>Almamater</label>
						<b-form-input placeholder="Masukkan Almamater" v-model="form.peg_almamater_nama"></b-form-input>
					</div>
				</div>
			</StepItem>
			<StepItem>
				<div class="border p-3 border-lightgray rounded">
					<h5>Penempatan</h5>
					<div class="mb-3">
						<label>Unit Kerja</label>
						<b-form-select class="font-size-13" v-model="form.unit_kerja_id" :options="UKEROptions"
							value-field="unit_kerja_id" text-field="unit_kerja_nama">
						</b-form-select>
					</div>
					<div class="mb-3">
						<label>Instalasi / Ruangan / Unit / Tim</label>
						<b-form-select class="font-size-13" v-model="form.ruangan_id" :options="RUANGANOptions"
							value-field="ruangan_id" text-field="nama_ruangan">
						</b-form-select>
					</div>
					<div class="mb-3">
						<label>Sebagai</label>
						<b-form-select class="font-size-13" v-model="form.role" :options="PEGRUANGANOptions"
							value-field="value" text-field="text">
						</b-form-select>
					</div>
					<div class="mb-3">
						<label>Spesialis</label>
						<b-form-select class="font-size-13" v-model="form.spesialis_id" :options="SPESOptions"
							value-field="spesialis_id" text-field="spesialis_nama"></b-form-select>
					</div>
					<div class="mb-0">
						<label>Sub Spesialis</label>
						<b-form-select class="font-size-13" v-model="form.sub_spesialis_id" :options="SUBSPESOptions"
							value-field="sub_spesialis_id" text-field="sub_spesialis_nama">
						</b-form-select>
					</div>
				</div>
			</StepItem>
			<StepItem>
				<div class="border p-3 border-lightgray rounded">
					<h5>Jabatan</h5>
					<div class="mb-3">
						<label>Jenis Jabatan</label>
						<b-form-select class="font-size-13" v-model="form.jenis_jabatan_id" :options="JJOptions"
							value-field="jenis_jabatan_id" text-field="jenis_jabatan_nama">
						</b-form-select>
					</div>
					<div class="mb-3">
						<label>Nama Jabatan</label>
						<b-form-select class="font-size-13" v-model="form.jabatan_id" :options="JBOptionsFilter"
							value-field="jabatan_id" text-field="jabatan_nama">
						</b-form-select>
					</div>
					<div class="mb-3">
						<label>Bidang</label>
						<b-form-select class="font-size-13" v-model="form.bidang_id" :options="BIDOptions"
							value-field="bidang_id" text-field="bidang_nama">
						</b-form-select>
					</div>
					<!-- <div class="mb-3">
						<label>Jabatan Fungsional</label>
						<b-form-select class="font-size-13" v-model="form.jenis_kelamin" :options="JKOptions">
						</b-form-select>
					</div> -->
					<div class="mb-3">
						<label>Golongan</label>
						<b-form-select class="font-size-13" v-model="form.golongan_id" :options="GOLOptions"
							value-field="golongan_id" text-field="golongan_nama">
						</b-form-select>
					</div>
					<div class="mb-0">
						<label>Kualifikasi Pendidikan Tenaga Kerja</label>
						<b-form-select class="font-size-13" v-model="form.jenis_tenaga_kerja_id" :options="JTKOptions"
							value-field="jenis_tenaga_kerja_id" text-field="jenis_tenaga_kerja_nama">
						</b-form-select>
					</div>
					<div class="mb-3">
						<div class="mb-2">
							<label>Status Kepegawaian</label>
							<b-form-select class="font-size-13" v-model="form.status_kepegawaian_id"
								:options="SPEGOptions" value-field="status_kepegawaian_id"
								text-field="status_kepegawaian" :disabled="!$checkPermission('edit-admin')">
							</b-form-select>
						</div>
					</div>
				</div>
			</StepItem>
			<StepItem>
				<div class="border p-3 border-lightgray rounded">
					<h5>Informasi Lainnya</h5>
					<div class="mb-3">
						<label>Nomor KTP</label>
						<b-form-input placeholder="Masukkan Nomor KTP" v-model="form.peg_ktp"
							:state="getErrorState('peg_ktp')">
						</b-form-input>
						<p style="color:red;" v-if="getErrorState('peg_ktp') === false">
							{{ getErrorMessage('peg_ktp') }}
						</p>
					</div>
					<div class="mb-3">
						<label>Nomor Kartu Keluarga</label>
						<b-form-input placeholder="Masukkan No Kartu Keluarga" v-model="form.peg_no_kk"></b-form-input>
					</div>
					<div class="mb-3">
						<label>NPWP</label>
						<b-form-input placeholder="Masukkan NPWP" v-model="form.peg_npwp"></b-form-input>
					</div>
					<div class="mb-3">
						<label>Nomor BPJS Kesehatan</label>
						<b-form-input placeholder="Masukkan No. BPJS Kesehatan" v-model="form.peg_bpjs"></b-form-input>
					</div>
					<div class="mb-3">
						<label>No. BPJS Ketenagakerjaan</label>
						<b-form-input placeholder="Masukkan No. BPJS Ketenagakerjaan"
							v-model="form.peg_bpjs_ketenagakerjaan">
						</b-form-input>
					</div>
					<div class="mb-3">
						<label>Nomor Rekening BJB</label>
						<b-form-input placeholder="Masukkan No Rekening BJB/Insentif" v-model="form.peg_nomor_rekening">
						</b-form-input>
					</div>
					<!-- <div class="mb-3">
						<label>Nomor Kontak Darurat</label>
						<b-form-input placeholder="Masukkan Nomor Kontak Darurat"></b-form-input>
					</div> -->
					<!-- <div class="mb-3">
						<label>Masa Kerja</label>
						<b-form-input placeholder="Masukkan Masa Kerja"></b-form-input>
					</div> -->
					<div class="mb-0">
						<label>Status Kerja</label>
						<b-form-select class="font-size-13" v-model="form.peg_status_kerja" :options="SKEROptions"
							value-field="value" text-field="text">
						</b-form-select>
					</div>
				</div>
			</StepItem>
		</FormWizard>
		<template #footer>
			<b-button variant="outline-darkgreen" v-if="activeStep == 1" class="mr-1 d-inline-flex align-items-center"
				@click="$emit('close')">Batalkan</b-button>
			<b-button variant="outline-darkgreen" v-else @click="prevStep()"
				class="mr-1 d-inline-flex align-items-center"><i style="font-size:17px"
					class="mb-0 d-inline-block bx bx-chevron-left"></i> Kembali</b-button>
			<b-button variant="darkgreen" class="d-inline-flex align-items-center" v-if="activeStep < 4"
				@click="nextStep()">Selanjutnya <i style="font-size:17px"
					class="mb-0 d-inline-block bx bx-chevron-right"></i></b-button>
			<b-button variant="darkgreen" class="d-inline-flex align-items-center" v-else @click="save()">
				<i style="font-size:17px" class="mb-0 d-inline-block bx bx-save"></i> Simpan
			</b-button>
		</template>
	</modal>
</template>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import FormWizard from '../Wizard/FormWizard.vue'
import StepItem from '../Wizard/StepItem.vue';
import { mapGetters } from 'vuex'
import { _ } from 'vue-underscore'

export default {
	props: ["open", "title", "size", "pegawai"],
	computed: {
		...mapGetters({
			KPOptions: 'useroptions/KPOptions',
			BIDOptions: 'useroptions/BIDOptions',
			PDOptions: 'useroptions/PDOptions',
			UKEROptions: 'useroptions/UKEROptions',
			RUANGANOptions: 'useroptions/RUANGANOptions',
			SPESOptions: 'useroptions/SPESOptions',
			JBOptions: 'useroptions/JBOptions',
			JJOptions: 'useroptions/JJOptions',
			GOLOptions: 'useroptions/GOLOptions',
			// JFOptions: 'useroptions/JFOptions',
			JTKOptions: 'useroptions/JTKOptions',
			SPEGOptions: 'useroptions/SPEGOptions',
		}),
		fullName() {
			let nama_tanpa_gelar = this.form.peg_nama_tanpa_gelar == null ? '' : this.form.peg_nama_tanpa_gelar
			let gelar_depan = this.form.peg_gelar_depan == null ? '' : this.form.peg_gelar_depan
			let gelar_belakang = this.form.peg_gelar_belakang == null ? '' : this.form.peg_gelar_belakang
			return this.form.peg_nama_lengkap = gelar_depan + ' ' + nama_tanpa_gelar + ' ' + gelar_belakang
		},
		JBOptionsFilter() {
			if (this.form.jenis_jabatan_id == 2) {
				return this.JBOptions.filter((jb) => jb.jenis_jabatan_id == 2 && jb.unit_kerja_id_struktural == this.form.unit_kerja_id)
			}
			return this.JBOptions.filter((jb) => jb.jenis_jabatan_id == this.form.jenis_jabatan_id)
		},
	},
	data() {
		return {
			form: {},
			activeStep: 1,
			loading: false,
			errors: {},
			AOptions: [
				{ value: '' || null, text: 'Pilih Agama' },
				{ value: 'Budha', text: 'Budha' },
				{ value: 'Hindu', text: 'Hindu' },
				{ value: 'Islam', text: 'Islam' },
				{ value: 'Katolik', text: 'Katolik' },
				{ value: 'Konghucu', text: 'Konghucu' },
				{ value: 'Kristen', text: 'Kristen' },
			],
			PEGRUANGANOptions: [
				{ value: '' || null, text: 'Pilih Tipe' },
				{ value: 'pegawai', text: 'Pegawai' },
				{ value: 'kepala', text: 'Kepala' },
				{ value: 'kepala tim 1', text: 'Kepala Tim 1' },
				{ value: 'kepala tim 2', text: 'Kepala Tim 2' },
				{ value: 'admin ruangan', text: 'Admin Ruangan' },
			],
			SPOptions: [
				{ value: '' || null, text: 'Pilih Status Pernikahan' },
				{ value: 'Menikah', text: 'Menikah' },
				{ value: 'Belum Menikah', text: 'Belum Menikah' },
			],
			GOptions: [
				{ value: '' || null, text: 'Pilih Golongan Darah' },
				{ value: 'A+', text: 'A+' },
				{ value: 'A-', text: 'A-' },
				{ value: 'B+', text: 'B+' },
				{ value: 'B-', text: 'B-' },
				{ value: 'AB+', text: 'AB+' },
				{ value: 'AB-', text: 'AB-' },
				{ value: 'O+', text: 'O+' },
				{ value: 'O-', text: 'O-' },
				{ value: 'A', text: 'A' },
				{ value: 'B', text: 'B' },
				{ value: 'AB', text: 'AB' },
				{ value: 'O', text: 'O' },
			],
			JKOptions: [
				{ value: '' || null, text: 'Pilih Jenis Kelamin' },
				{ value: 'L', text: 'Laki Laki' },
				{ value: 'P', text: 'Perempuan' },
			],
			SKEROptions: [
				{ value: '' || null, text: 'Pilih Status Kerja' },
				{ value: '0', text: 'Aktif' },
				{ value: '1', text: 'Tidak Aktif' },
			],
			SUBSPESOptions: [],
			UKERROptions: [],
			UKERUROptions: [],
		};
	},
	watch: {
		open() {
			this.resetForm()
			if (this.pegawai) {
				this.form = _.clone(this.pegawai)
			} else {
				this.form = {}
			}
		},
		"form.spesialis_id"() {
			this.getSubSpes()
		},
		// "form.jabatan_id"() {
		//   this.getJabFung()
		// },
		"form.tenaga_kerja_id"() {
			this.getJenTenKer()
		},
	},
	components: { FormWizard, StepItem },
	methods: {
		async save() {
			this.loading = true
			try {
				if (this.form.peg_id) {
					let resp = (await axios.patch('/pegawai/' + this.form.peg_id, this.form)).data
					if (resp.success) {
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: 'Data berhasil disimpan',
							confirmButtonText: 'ok',
						})
					}
				} else {
					let resp = (await axios.post('/pegawai/', this.form)).data
					if (resp.success) {
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: 'Data berhasil disimpan',
							confirmButtonText: 'ok',
						})
					}
				}
				this.activeStep = 1
				this.$emit('close')
				this.$emit('onSave')
				this.errors = {}
			} catch (err) {
				if (err.response && err.response.status == 422) {
					this.errors = err.response.data.errors
				}
			}
			this.loading = false
		},
		cancelPegawai() {
			this.$router.push({ name: 'kepegawaian' })
		},
		setActiveStep(value) {
			this.activeStep = value
		},
		nextStep() {
			this.$refs.wizard.$refs.controls[0].nextStep();
		},
		prevStep() {
			this.$refs.wizard.$refs.controls[0].prevStep();
		},
		// async save() {
		// 	alert('save')
		// 	this.$emit('close')
		// },
		getErrorState(key) {
			if (this.errors[key]) {
				return false
			}
			return null
		},
		getErrorMessage(key) {
			if (this.errors[key]) {
				return this.errors[key].join(', ')
			}
			return null
		},
		resetForm() {
			this.errors = {}
		},
		async getSubSpes() {
			let subSpesResp = (await axios.get('/pegawai/sub-spesialis', {
				params: {
					spesialis_id: this.form.spesialis_id
				}
			})).data.data
			let placeholderSubSpes = [{
				sub_spesialis_id: null, sub_spesialis_nama: "Pilih Sub Spesialis"
			}]
			this.SUBSPESOptions = placeholderSubSpes.concat(subSpesResp)
		},
	}
}
</script>
