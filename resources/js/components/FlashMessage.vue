<script setup>
import { usePage } from '@inertiajs/vue3';
import { watch } from 'vue';
import { message } from 'ant-design-vue';

const page = usePage();

// Watch for flash messages dynamically
watch(() => page.props.flash, (flash) => {
    if (flash?.message) {
        message.success(flash.message);
    }
    if (flash?.error) {
        message.error(flash.error);
    }
});
</script>

<template>
    <!-- Empty template, because we only need to watch for messages -->
</template>
