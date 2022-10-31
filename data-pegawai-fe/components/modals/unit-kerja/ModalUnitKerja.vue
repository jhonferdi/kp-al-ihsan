<template>
    <modal :open="open" :size="size" @close="$emit('close')" :title="title">
        <label>Pilih Wakil Direktur</label>
        <b-form-select class="font-size-13" v-model="form.wd_id" :options="WDOptions" value-field="unit_kerja_id"
            text-field="unit_kerja_nama">
        </b-form-select>
        <label>Unit Kerja</label>
        <b-form-input placeholder="Masukkan Unit Kerja" v-model="form.unit_kerja_nama"
            :state="getErrorState('unit_kerja_nama')">
        </b-form-input>
        <p style="color:red;" v-if="getErrorState('unit_kerja_nama') === false">
            {{ getErrorMessage('unit_kerja_nama') }}
        </p>
        <template #footer>
            <b-button variant="darkgreen" @click="save()" :disabled="loading">{{ loading ? 'Sedang Menyimpan' : 'Simpan'
            }}
            </b-button>
        </template>
    </modal>
</template>

<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import { _ } from 'vue-underscore'

export default {
    // middleware: 'auth',
    middleware: ['auth'],
    props: ['open', 'title', 'size', 'unitKerja', 'WDOptions'],
    data() {
        return {
            loading: false,
            form: {},
            errors: {},
        }
    },
    watch: {
        open() {
            this.resetForm()
            if (this.unitKerja) {
                this.form.wd_id = _.clone(this.unitKerja.unit_kerja_parent)
                this.form.unit_kerja_nama = _.clone(this.unitKerja.unit_kerja_nama)
                this.form.unit_kerja_id = _.clone(this.unitKerja.unit_kerja_id)
            }
        },
    },
    methods: {
        async save() {
            this.loading = true
            try {
                let resp = (await axios.patch('unit-kerja/' + this.form.unit_kerja_id, {
                    wd_id: this.form.wd_id,
                    unit_kerja_nama: this.form.unit_kerja_nama,
                    unit_kerja_id: this.form.unit_kerja_id,
                    save_unit_kerja: true
                })).data
                if (resp.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Data berhasil disimpan',
                        confirmButtonText: 'ok',
                    })
                }
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
    }
}
</script>
