<template>
    <!--<v-img src="/img/ynit_logo.png" :aspect-ratio="474 / 282" />-->
    <h1 style="text-align: center" :aria-label="camelPad(name)" translate="no">
        {{ name }}
    </h1>
</template>
<script lang="ts" setup>
const name = import.meta.env.VITE_APP_NAME
</script>
