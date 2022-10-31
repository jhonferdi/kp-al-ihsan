<template>
    <div>
        <div class="card">
            <div class="card-header d-sm-flex justify-content-between py-3">
            <h5 class="d-flex align-items-center mb-2 text-capitalize"> {{judul}}
                <span class="h3 m-0 ml-2 text-light-green" title="Sudah Terupload">
                    <i :class="uploaded[id] ? 'feather icon-check-circle' : ''"></i></span>
                </h5>
                <div>
                <input
                    :ref="id"
                    @change="doUploadFile(id)"
                    class="d-none"
                    type="file"
                    :accept="accept?accept:'application/pdf'"
                    :disabled="disabled == 1" v-bind:readonly="readonly"
                    required
                >
                <button v-if="!cekKelengkapan" :class="loading ? 'text-nowrap btn btn-primary btn-sm btn-loading disabled' : 'text-nowrap btn btn-primary btn-sm'" @click="btnUpload(id)">
                    <i :class="uploaded ? 'feather icon-edit' : 'fas fa-upload'"></i>
                    {{ loading ? 'Sedang mengunggah...' : (uploaded[id] ? 'Ubah File' : 'Upload File') }}
                </button>
                </div>
            </div>
            <div class="card-body d-md-flex justify-content-between" v-if="uploaded[id]">
                <div>
                <i class="fas fa-file-pdf text-danger mr-2 f-20"></i>
                <p  class="f-13 text-muted m-0 d-inline-block">
                    <a href="#" class="" @click="btnView(id, $event)" type="button" data-toggle="modal" :data-target="'#'+id+'Modal'"> {{ fileName[id] }}</a>
                    <!-- : {{ fileName.pengawasSekolah }} -->
                </p>
                </div>
            </div>
        </div>
        
        <div v-if="readFile">
        <div :id="id+'Modal'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLiveLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLiveLabel">{{ pdfFileName }}</h5>
                </div>
                <div class="modal-body">
                <embed
                    :src="readFilePdf"
                    style="width:100%;height:440px"
                />
                </div>
                <div class="modal-footer">
                <button class="btn btn-primary btn-sm" data-dismiss="modal" @click="btnClose()">
                    Tutup
                </button>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex"
import Form from "vform"
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
    name: 'upload-file',
    props:[
        'judul',
        'id',
        'uploaded',
        'fileName',
        'viewFile',
        'url',
        'accept'
    ],
    data() {
        return {
            disabled: 0,
            readonly: false,
            cekKelengkapan: false,
            loading: false,
            readFile: false,
            readFilePdf: null,
            pdfFileName: null,
            fileError: 'Jenis File Tidak Sesuai Atau Ukuran File Melebihi 5MB'
        }
    },
    
    methods: {
        btnClose () {
        this.pdfFileName = null
        this.readFilePdf = null
        this.readFile = false
        },
        btnView (name, event) {
            event.preventDefault()
            const view = this.getFile(name)
            this.pdfFileName = this.fileName[this.id]
            this.readFilePdf = ''
            this.readFilePdf = this.viewFile[this.id]
            this.readFile = true
        },
        getFile (name) {
            const arr = this.viewFile[this.id].split(',')
            if (arr.length == 1) {
                return arr[0]
            }
            const mime = arr[0].match(/:(.*?);/)[1]
            const bstr = atob(arr[1]) 
            let n = bstr.length
            const u8arr = new Uint8Array(n)

            while(n--){
                u8arr[n] = bstr.charCodeAt(n)
            }

            return new File([u8arr], this.fileName[this.id], {type:mime})
        },
        btnUpload (name) {
        this.$refs[name].click()
        },
        inArray(needle, haystack) {
            console.log(needle,haystack);
            var length = haystack.length;
            for(var i = 0; i < length; i++) {
                if(haystack[i] == needle) return true;
            }
            return false;
        },
        async doUploadFile (name) {
            const file = this.$refs[name].files[0]
            if (((this.inArray(file.type,(!this.accept ? ['application/pdf'] : this.accept.split(",")))) && file.size < 5242880)) {
                var formData = new FormData();
                formData.append("file", file);
                this.loading = true
                let resp = await axios.post("/upload-file", formData, {
                  headers: {
                    "Content-Type": "multipart/form-data",
                  },
                });
                const reader = new FileReader()
                reader.fileName = file.name
                reader.onload = (event) => {
                if (resp.data.success) {
                    this.loading = false
                    this.uploaded[this.id] = true
                    this.fileName[this.id] = event.target.fileName
                    this.viewFile[this.id] = event.target.result
                    this.url[this.id] = resp.data.url
                    this.pdfFileName = null
                    this.readFilePdf = null
                    this.readFile = false
                }
                this.loading = false;
                }
                reader.readAsDataURL(file)
            } else {
                const input = this.$refs[name]
                input.type = 'text'
                input.type = 'file'
                // this.$swal.fire("Failed", this.fileError, "warning")
                Swal.fire({
                        icon: 'warning',
                        title: 'Failed',
                        text: this.fileError,
                        confirmButtonText: 'Ok'
                      })
            }
            }
        }
    }
</script>