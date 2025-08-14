<template>
    <Head title="Dashboard" />
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-chart-line text-blue-600"></i> Dashboard
        </h1>
        <nav class="mt-1 text-sm text-gray-500 flex items-center gap-2">
            <a href="#" class="hover:text-blue-600 flex items-center gap-1">
              <i class="fas fa-home"></i> Home
            </a>
            <span>/</span>
            <span class="text-gray-800 font-medium flex items-center gap-1">
              <i class="fas fa-tachometer-alt"></i> Dashboard
            </span>
        </nav>
    </div>

</template>

<script>
import Layout from '../../Shared/Layout.vue';

export default {
  layout: Layout,
};
</script>
