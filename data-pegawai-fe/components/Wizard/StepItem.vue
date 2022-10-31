<template>
  <div v-show="visible">
    <slot></slot>
  </div>
</template>
<script>
export default {
  props:{
    validated: Boolean
  },
  inject:['FormWizard'],
  data() {
    return {
      index: null,
      isValid:true,
    };
  },
  computed: {
    visible() {
      return this.index == this.FormWizard.active;
    }
  },
  watch:{
    validated(){
      this.FormWizard.steps.find(e=> e.id == this.index).isValid = this.validated
    }
  },
  
  created() {
    this.FormWizard.count++
    this.index = this.FormWizard.count
    this.FormWizard.steps.push({ id: this.index, isValid:this.isValid })
  }
}
</script>