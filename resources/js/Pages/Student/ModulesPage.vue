<template>
  <Head title="My Modules" />
  <div class="p-6 bg-gray-100 min-h-screen space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
        <i class="fas fa-book text-blue-600"></i> My Modules
      </h1>
      <input v-model="search" type="text" placeholder="Search module code/name..." class="px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring focus:ring-blue-400 text-sm w-64" />
    </div>

    <div class="bg-white rounded-xl shadow p-4">
      <table class="w-full table-auto text-sm">
        <thead>
          <tr class="text-left text-gray-600 border-b">
            <th class="py-3 px-2">Code</th>
            <th class="py-3 px-2">Name</th>
            <th class="py-3 px-2">Department</th>
            <th class="py-3 px-2">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="m in modules.data" :key="m.id" class="border-b hover:bg-gray-50">
            <td class="py-3 px-2">{{ m.code }}</td>
            <td class="py-3 px-2">{{ m.name }}</td>
            <td class="py-3 px-2">{{ m.department?.name }}</td>
            <td class="py-3 px-2">
              <Link :href="`/student/modules/${m.id}`" class="text-blue-600 hover:underline">View</Link>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-if="modules.data.length === 0" class="text-center text-gray-500 py-8">No modules found.</div>
      <div class="mt-4 flex justify-center"><Pagination :links="modules.links" /></div>
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
  props: { modules: Object, filters: Object },
  setup(props) {
    const search = ref(props.filters?.search || '');
    watch(search, (value) => {
      router.get('/student/modules', { search: value }, { preserveState: true, replace: true });
    });
    return { search };
  },
};
</script>

<style scoped>
</style>


