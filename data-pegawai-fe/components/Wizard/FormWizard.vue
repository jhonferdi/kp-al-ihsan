<template>
  <div>
    <step-navigation
      :steps="FormWizard.steps"
      :currentstep="currentstep">
    </step-navigation>

    <div class="my-4 mt-6">
      <slot></slot>
    </div>

    <step-controls
      ref="controls"
      v-for="(step,i) in FormWizard.steps"
      :step="step"
      :stepcount="FormWizard.steps.length"
      :currentstep="currentstep"
      @step-change="stepChanged"
      @check-validation="checkValidation"
      :key="i">
    </step-controls>
  </div>
</template>
<script>
import StepControls from "./StepControls.vue";
import StepNavigation from "./StepNavigation.vue";
export default{
  components: { StepControls, StepNavigation },
  props:{
    noBackButton: Boolean,
    finishText: String,
    sequential: Boolean,
    hideControls: Boolean,
  },
  data: () => ({
    currentstep: 1,
    indicatorclass: true,
    step: 1,
    active: 1,
    firststep: true,
    nextStep: "",
    lastStep: "",
    laststep: false,
    FormWizard:{
      count:0,
      active:1,
      steps:[],
      lastVisitedStep:1,
    },
  }),
  provide () {
    return {
      FormWizard: this.FormWizard,
      finish: this.finish,
      stepTo: this.stepChanged,
      noBackButton: this.noBackButton,
      finishText: this.finishText,
      sequential: this.sequential,
      hideControls: this.hideControls,
    }
  },
  methods: {
    stepChanged(step) {
      this.currentstep = step;
      this.FormWizard.active = step;
      this.$emit('activestep',step)
    },
    async finish(){
      await this.checkValidation(this.FormWizard.count)
      if(this.FormWizard.steps.find(e=>e.id == this.FormWizard.count ).isValid){
        this.$emit('finish')
      }
    },
    checkValidation(stepNumber){
      this.$emit('check-validation', stepNumber)
    },
  },
}
</script>