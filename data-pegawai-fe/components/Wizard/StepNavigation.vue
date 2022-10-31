<template>
  <div class="border rounded border-lightgray p-2 px-3">
    <ol class="p-0 d-flex mb-0 align-items-center m-0 justify-content-center" style="list-style:none">
      <StepNavigationItem 
        v-for="(step,i) in steps"
        :step="step"
        :currentstep="currentstep"
        :number="i+1"
        :steps="steps"
        :key="i"
      />
    </ol>
  </div>
</template>
<script>
import StepNavigationItem from './StepNavigationItem.vue';
export default {
    props: ["steps", "currentstep"],
    components: { StepNavigationItem }
}
</script>