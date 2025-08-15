<template>
  <Head :title="`${exam.module_code} - ${exam.module_name}`" />
  <div class="p-6 bg-gray-100 min-h-screen space-y-6">
    <div class="mb-4">
      <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
        <i class="fas fa-users text-blue-600"></i> {{ exam.module_code }} - {{ exam.module_name }}
      </h1>
      <p class="text-sm text-gray-500">Exam Date: {{ exam.exam_date }}</p>
    </div>

    <!-- Search -->
    <div class="flex justify-end mb-4">
      <input
        v-model="search"
        type="text"
        placeholder="Search by name or regnum..."
        class="px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring focus:ring-blue-400 text-sm w-full md:w-64"
      />
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-xl shadow p-4">
      <table class="w-full table-auto text-sm">
        <thead>
          <tr class="text-left text-gray-600 border-b">
            <th class="py-3 px-2">Name</th>
            <th class="py-3 px-2">Reg Number</th>
            <th class="py-3 px-2">Department</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="student in students.data" :key="student.id" class="border-b hover:bg-gray-50">
            <td class="py-3 px-2">{{ student.firstnames }} {{ student.surname }}</td>
            <td class="py-3 px-2">{{ student.regnum }}</td>
            <td class="py-3 px-2">{{ student.department?.name }}</td>
          </tr>
        </tbody>
      </table>

      <div v-if="students.data.length === 0" class="text-center text-gray-500 py-8">
        No students found.
      </div>

      <div class="mt-6 flex justify-center">
        <Pagination :links="students.links" />
      </div>
    </div>
  </div>
</template>

<script>
import Layout from '../../Shared/Layout.vue';
import Pagination from '../../Shared/Pagination.vue';
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';

export default {
  layout: Layout,
  components: { Pagination },
  props: {
    exam: Object,
    students: Object,
    filters: Object,
  },
  setup(props) {
    const search = ref(props.filters?.search || '');

    watch(search, (value) => {
      router.get(`/admin/invigilations/${props.exam.id}`, { search: value }, {
        preserveState: true,
        replace: true,
      });
    });

    return { search };
  },
};
</script>

<style scoped>
</style>


