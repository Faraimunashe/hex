<template>
  <Head title="Invigilation" />
  <div class="p-6 bg-gray-100 min-h-screen space-y-6">
    <div class="mb-4">
      <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
        <i class="fas fa-user-shield text-blue-600"></i> Invigilation
      </h1>
      <nav class="mt-1 text-sm text-gray-500 flex items-center gap-2">
        <a href="#" class="hover:text-blue-600 flex items-center gap-1">
          <i class="fas fa-home"></i> Home
        </a>
        <span>/</span>
        <span class="text-gray-800 font-medium flex items-center gap-1">
          Invigilation
        </span>
      </nav>
    </div>

    <!-- Actions & Search -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
      <div class="flex items-center gap-3 w-full md:w-auto">
        <button @click="allocate" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition flex items-center gap-2">
          <i class="fas fa-random"></i> Allocate Invigilators
        </button>
        <button @click="notifyAll" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition flex items-center gap-2">
          <i class="fas fa-bell"></i> Notify All
        </button>
      </div>

      <div class="flex items-center gap-3 w-full md:w-auto">
        <input
          v-model="search"
          type="text"
          placeholder="Search module code/name..."
          class="px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring focus:ring-blue-400 text-sm w-full md:w-64"
        />
      </div>
    </div>

    <!-- Examinations Table -->
    <div class="bg-white rounded-xl shadow p-4">
      <table class="w-full table-auto text-sm">
        <thead>
          <tr class="text-left text-gray-600 border-b">
            <th class="py-3 px-2">Module Code</th>
            <th class="py-3 px-2">Module Name</th>
            <th class="py-3 px-2">Exam Date</th>
            <th class="py-3 px-2">Start Time</th>
            <th class="py-3 px-2">End Time</th>
            <th class="py-3 px-2">Students</th>
            <th class="py-3 px-2">Invigilators</th>
            <th class="py-3 px-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="exam in examinations.data" :key="exam.id" class="border-b hover:bg-gray-50">
            <td class="py-3 px-2">{{ exam.module_code }}</td>
            <td class="py-3 px-2">{{ exam.module_name }}</td>
            <td class="py-3 px-2">{{ exam.exam_date }}</td>
            <td class="py-3 px-2">{{ exam.start_time }}</td>
            <td class="py-3 px-2">{{ exam.end_time }}</td>
            <td class="py-3 px-2">
              <Link :href="`/admin/invigilations/${exam.id}`" class="text-blue-600 hover:underline">
                {{ exam.student_count }}
              </Link>
            </td>
            <td class="py-3 px-2">
              <div class="flex flex-wrap gap-1">
                <span v-for="inv in exam.invigilators" :key="inv" class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">{{ inv }}</span>
              </div>
            </td>
            <td class="py-3 px-2">
              <button @click="notify(exam.id)" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-xs">
                <i class="fas fa-bell"></i> Notify
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-if="examinations.data.length === 0" class="text-center text-gray-500 py-8">
        No upcoming exams found.
      </div>

      <div class="mt-6 flex justify-center">
        <Pagination :links="examinations.links" />
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
  props: { examinations: Object, filters: Object },
  setup(props) {
    const search = ref(props.filters?.search || '');

    watch(search, (value) => {
      router.get('/admin/invigilations', { search: value }, {
        preserveState: true,
        replace: true,
      });
    });

    const allocate = () => {
      router.post('/admin/invigilations/allocate');
    };
    const notifyAll = () => {
      router.post('/admin/invigilations/notify-all');
    };
    const notify = (id) => {
      router.post(`/admin/invigilations/${id}/notify`);
    };

    return { search, allocate, notifyAll, notify };
  },
};
</script>

<style scoped>
</style>


