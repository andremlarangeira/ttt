<script setup>
defineProps(['dialogVisible'])
</script>
<template>
    <div
        class="fixed inset-0 z-[999] grid h-screen w-screen place-items-center bg-black bg-opacity-40 backdrop-blur-sm transition-opacity duration-300"
        v-if="dialogVisible"
    >
        <slot></slot>
    </div>
</template>
