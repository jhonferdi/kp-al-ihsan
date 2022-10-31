<template>
    <div class="border rounded-lg p-2 px-3 mb-2" style="border-color:#898989 !important">
        <h6 class="text-muted text-small font-weight-bold mb-1">{{label}}</h6>
        <div class="font-bold text-sm">
            <slot></slot>
        </div>
    </div>
</template>
<script>
export default {
    props:['label']
}
</script>