<template>
    <modal :open="open" :size="size" @close="$emit('close')" :title="title">
        <label>Pilih Instalasi</label>
        <b-form-select class="font-size-13" v-model="form.instalasi_id" :options="INSOptions" value-field="ruangan_id"
            text-field="nama_ruangan">
        </b-form-select>
        <label>Ruangan</label>
        <b-form-input placeholder="Masukkan Ruangan" v-model="form.nama_ruangan"
            :state="getErrorState('nama_ruangan')">
        </b-form-input>
        <p style="color:red;" v-if="getErrorState('nama_ruangan') === false">
            {{ getErrorMessage('nama_ruangan') }}
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
    props: ['open', 'title', 'size', 'ruangan', 'INSOptions'],
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
            if (this.ruangan) {
                this.form.instalasi_id = _.clone(this.ruangan.ruangan_parent)
                this.form.nama_ruangan = _.clone(this.ruangan.nama_ruangan)
                this.form.ruangan_id = _.clone(this.ruangan.ruangan_id)
            }
        },
    },
    methods: {
        async save() {
            this.loading = true
            try {
                let resp = (await axios.patch('unit-kerja/' + this.form.ruangan_id, {
                    nama_ruangan: this.form.nama_ruangan,
                    instalasi_id: this.form.instalasi_id,
                    save_ruangan: true
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
