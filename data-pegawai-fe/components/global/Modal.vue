<template>
  <Transition>
    <div v-if="open">
      <div class="position-relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="z-index:1002;">
        <div class="position-fixed z-10 w-100" style="overflow: auto; z-index:1002; top:0; left:0; overflow: -webkit-paged-y;overflow-y: auto; height:100vh;">
          <div class="position-fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" style="background:rgba(0,0,0,.3); width:100vw; height: 100vh; overflow-y:auto" @click="$emit('close')"></div>
          <div class="d-flex  align-items-center w-100 justify-content-center min-h-full p-4 text-center sm:p-0" style="min-height:100%">
            <div class="position-relative bg-white rounded-lg text-left shadow transform transition-all sm:my-8 w-100 mb-3" :style="[{maxWidth: modalSizes[size] || '800px'}]" :class="size ? `max-w-${size}` : 'sm:max-w-2xl'">
              <div class="bg-white border-b rounded-lg border-gray-300 px-4 py-3 d-flex justify-content-between align-items-center">
                <h5 class="font-bold mb-0" v-if="title">{{title}}</h5>
                <b-icon icon="x-lg" style="cursor:pointer; font-size: 20px; align-self:flex-end;" class="text-lightgreen mb-1" @click="$emit('close')" />
              </div>
              <div class="bg-gray-50 px-3 py-3">
                <slot></slot>
              </div>
              <div class="bg-white px-3 py-3 d-flex rounded-lg justify-content-end ">
                <slot name="footer">
                  <b-button variant="white" type="button" class="d-inline-flex justify-content-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 shadow-sm" @click="$emit('close')">Tutup</b-button>
                </slot>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script>
export default {
  name:"Modal",
  props:['title','size', 'open'],
  data: ()=>({
    modalSizes:{
      'sm': '640px',
      'md': '768px',
      'lg': '1024px',
      'xl': '1280px',
    }
  })
}
</script>
<style scoped>
  .inset-0{
    top: 0px;
    right: 0px;
    bottom: 0px;
    left: 0px;
  }
  .bg-gray-50{
    background-color: #F4F8F9;
  }
  /* we will explain what these classes do next! */
  .v-enter-active,
  .v-leave-active {
    transition: opacity 0.5s ease;
  }

  .v-enter-from,
  .v-leave-to {
    opacity: 0;
  }
</style>

