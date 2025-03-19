<script setup lang="ts">
import { defineProps } from 'vue';

defineProps<{
    title: string;
    value: number | string;
    icon: any;
    bgColor?: string;
}>();
</script>

<template>
    <a-card class="dashboard-card">
        <div class="grid grid-cols-2">
            <div>
                <h3 class="text-xl text-gray-400">{{ title }}</h3>
                <h4 class="text-2xl font-bold mb-0">{{ value }}</h4>
            </div>
            <div class="flex justify-end">
                <div class="badge-icon flex justify-center items-center text-white" :class="bgColor">
                    <component :is="icon" />
                </div>
            </div>
        </div>
    </a-card>
</template>

<style scoped>
.badge-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
