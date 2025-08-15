<template>
  <Head :title="`${module.code} - ${module.name}`" />
  <div class="p-6 bg-gray-100 min-h-screen space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
          <i class="fas fa-book text-blue-600"></i>
          {{ module.code }} - {{ module.name }}
        </h1>
        <div class="text-sm text-gray-600">Department: {{ module.department?.name }}</div>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow p-4">
      <h2 class="font-semibold text-gray-800 mb-3">Registered Students</h2>
      <table class="w-full table-auto text-sm">
        <thead>
          <tr class="text-left text-gray-600 border-b">
            <th class="py-3 px-2">Name</th>
            <th class="py-3 px-2">Reg Number</th>
            <th class="py-3 px-2">Department</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="row in students.data" :key="row.id" class="border-b hover:bg-gray-50">
            <td class="py-3 px-2">{{ row.student?.user?.name }}</td>
            <td class="py-3 px-2">{{ row.student?.regnum }}</td>
            <td class="py-3 px-2">{{ row.student?.department?.name }}</td>
          </tr>
        </tbody>
      </table>
      <div v-if="students.data.length === 0" class="text-center text-gray-500 py-8">No students found.</div>
      <div class="mt-4 flex justify-center"><Pagination :links="students.links" /></div>
    </div>
  </div>
</template>

<script>
import Layout from '../../Shared/Layout.vue';
import Pagination from '../../Shared/Pagination.vue';

export default {
  layout: Layout,
  components: { Pagination },
  props: { module: Object, students: Object },
};
</script>

<style scoped>
</style>


