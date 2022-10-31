<template>
  <div class="d-flex text-sm justify-content-between" v-if="active && !hideControls">
    <div><button class="border border-darkgreen hover:text-white bg-transparent text-darkgreen p-2 px-4 rounded-lg whitespace-nowrap font-bold" v-if="!firststep && !noBackButton" @click="prevStep()" :disabled="firststep">Sebelumnya</button></div>
    <div :style="[noBackButton ? {flex:1} : {}]">
      <b-button :class="noBackButton ? 'grow w-full block' :''" class="bg-darkgreen text-white  hover:bg-teal-700 transition p-2 px-4 rounded-lg ml-auto whitespace-nowrap font-bold" v-if="laststep" @click="finish()">{{ finishText || 'Simpan'}}</b-button>
      <b-button :class="noBackButton ? 'grow w-full block' :''" class="bg-darkgreen text-white  hover:bg-teal-700 transition p-2 px-4 rounded-lg ml-auto whitespace-nowrap font-bold" v-else @click="nextStep()" :disabled="laststep">Selanjutnya</b-button>
    </div>
  </div>
</template>
<script>
export default {
  props: ['step', 'stepcount', 'currentstep'],
  inject: ['finish','noBackButton','finishText','FormWizard','hideControls'],
  computed: {
    active: function() {
      return (this.step.id == this.currentstep)
    },

    firststep: function() {
      return (this.currentstep == 1)
    },

    laststep: function() {
      return (this.currentstep == this.stepcount)
    }
  },

  methods: {
    checkValidation(){
      this.$emit('check-validation', this.step.id)
    },
    async nextStep() {
      await this.checkValidation()
      if(!this.step.isValid) {
        return
      }

      if(this.FormWizard.lastVisitedStep < this.FormWizard.count){
        this.FormWizard.lastVisitedStep++
      }
      this.$emit('step-change', this.currentstep + 1)
    },

    prevStep() {
      this.$emit('step-change', this.currentstep - 1)
    }
  }
}
</script>