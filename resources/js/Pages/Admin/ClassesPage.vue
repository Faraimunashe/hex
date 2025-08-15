<template>
  <Head title="Classes" />
  <div class="p-6 bg-gray-100 min-h-screen space-y-6">
    <div class="mb-4">
      <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
        <i class="fas fa-layer-group text-blue-600"></i> Classes
      </h1>
      <nav class="mt-1 text-sm text-gray-500 flex items-center gap-2">
        <a href="#" class="hover:text-blue-600 flex items-center gap-1">
          <i class="fas fa-home"></i> Home
        </a>
        <span>/</span>
        <span class="text-gray-800 font-medium flex items-center gap-1">
          Classes
        </span>
      </nav>
    </div>

    <!-- Actions & Search -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
      <div class="flex items-center gap-3 w-full md:w-auto">
        <button @click="registerAll" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition flex items-center gap-2">
          <i class="fas fa-user-plus"></i> Register All Students
        </button>
        <button @click="deregisterAll" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition flex items-center gap-2">
          <i class="fas fa-user-minus"></i> Deregister All Students
        </button>
      </div>

      <div class="flex items-center gap-3 w-full md:w-auto">
        <input
          v-model="search"
          type="text"
          placeholder="Search modules by name/code..."
          class="px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring focus:ring-blue-400 text-sm w-full md:w-64"
        />
      </div>
    </div>

    <!-- Modules Table -->
    <div class="bg-white rounded-xl shadow p-4">
      <table class="w-full table-auto text-sm">
        <thead>
          <tr class="text-left text-gray-600 border-b">
            <th class="py-3 px-2">Module Name</th>
            <th class="py-3 px-2">Students Total</th>
            <th class="py-3 px-2">Department</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in classes.data" :key="item.id" class="border-b hover:bg-gray-50">
            <td class="py-3 px-2">{{ item.name }}</td>
            <td class="py-3 px-2">
              <Link :href="`/admin/classes/${item.id}`" class="text-blue-600 hover:underline">
                {{ item.students_total }}
              </Link>
            </td>
            <td class="py-3 px-2">{{ item.department }}</td>
          </tr>
        </tbody>
      </table>

      <div v-if="classes.data.length === 0" class="text-center text-gray-500 py-8">
        No modules found.
      </div>

      <div class="mt-6 flex justify-center">
        <Pagination :links="classes.links" />
      </div>
    </div>
  </div>
</template>

<script>
import Layout from '../../Shared/Layout.vue';
import Pagination from '../../Shared/Pagination.vue';
import { ref, watch } from 'vue';
import { router, Link } from '@inertiajs/vue3';

export default {
  layout: Layout,
  components: { Pagination, Link },
  props: {
    classes: Object,
    filters: Object,
  },
  setup(props) {
    const search = ref(props.filters?.search || '');

    watch(search, (value) => {
      router.get('/admin/classes', { search: value }, {
        preserveState: true,
        replace: true,
      });
    });

    const registerAll = () => {
      router.post('/admin/classes/register-all');
    };

    const deregisterAll = () => {
      router.delete('/admin/classes/deregister-all');
    };

    return { search, registerAll, deregisterAll };
  },
};
</script>

<style scoped>
</style>


