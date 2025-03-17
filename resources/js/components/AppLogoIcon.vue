<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import logo from '../../../public/assets/images/logo.svg'

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <img :src="logo" alt="logo" :class="className" v-bind="$attrs">
</template>
