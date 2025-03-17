<script setup lang="ts">
import { ref, watch, defineProps } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { UserOutlined, DatabaseOutlined } from '@ant-design/icons-vue';

// Accept collapsed as a prop
defineProps<{ collapsed: boolean }>();

// Define selectedKeys
const selectedKeys = ref<string[]>([]);

// Watch for route changes and update selectedKeys
const page = usePage();
watch(() => page.url, (newUrl) => {
  if (newUrl.includes('dashboard')) selectedKeys.value = ['1'];
  else if (newUrl.includes('categories')) selectedKeys.value = ['2'];
  else if (newUrl.includes('brands')) selectedKeys.value = ['3'];
  else if (newUrl.includes('products')) selectedKeys.value = ['4'];
  else if (newUrl.includes('purchase')) selectedKeys.value = ['5'];
  else if (newUrl.includes('order')) selectedKeys.value = ['6'];
  else if (newUrl.includes('profile')) selectedKeys.value = ['7'];
});
</script>

<template>
  <a-layout-sider :collapsed="collapsed" :trigger="null" collapsible>
    <div class="logo">
      <h3 v-if="!collapsed">MyApp</h3>
    </div>
    <a-menu v-model:selectedKeys="selectedKeys" theme="dark" mode="inline">
      <a-menu-item key="1">
        <Link :href="route('user.dashboard')">
          <DatabaseOutlined />
          <span>Dashboard</span>
        </Link>
      </a-menu-item>
      <a-menu-item key="2">
        <Link :href="route('user.categories')">
          <UserOutlined />
          <span>Categories</span>
        </Link>
      </a-menu-item>
      <a-menu-item key="3">
        <Link :href="route('user.brands')">
          <UserOutlined />
          <span>Brands</span>
        </Link>
      </a-menu-item>
      <a-menu-item key="4">
        <Link :href="route('user.products')">
          <UserOutlined />
          <span>Products</span>
        </Link>
      </a-menu-item>
      <a-menu-item key="5">
        <Link :href="route('user.purchase.product.list')">
          <UserOutlined />
          <span>Purchase Products</span>
        </Link>
      </a-menu-item>
      <a-menu-item key="6">
        <Link :href="route('user.order.create')">
          <UserOutlined />
          <span>Get Orders</span>
        </Link>
      </a-menu-item>
      <a-menu-item key="7">
        <Link :href="route('profile.edit')">
          <UserOutlined />
          <span>Profile</span>
        </Link>
      </a-menu-item>
    </a-menu>
  </a-layout-sider>
</template>

<style scoped>
.logo {
  height: 32px;
  background: rgba(255, 255, 255, 0.3);
  margin: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  color: white;
}
</style>
