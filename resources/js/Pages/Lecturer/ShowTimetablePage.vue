<template>
  <Head :title="`${exam.module?.code} - ${exam.module?.name}`" />
  <div class="p-6 bg-gray-100 min-h-screen space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
          <i class="fas fa-clipboard-check text-blue-600"></i>
          {{ exam.module?.code }} - {{ exam.module?.name }}
        </h1>
        <div class="text-sm text-gray-600">
          {{ exam.exam_date }} • {{ exam.start_time }} - {{ exam.end_time }} • {{ exam.venue?.name || 'TBA' }}
        </div>
      </div>
      <div class="flex gap-2">
        <Link :href="`/lecturer/timetable/${exam.id}/reports`" class="px-3 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">
          <i class="fas fa-file-alt"></i> Reports
        </Link>
      </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
      <!-- Students -->
      <div class="bg-white rounded-xl shadow p-4">
        <h2 class="font-semibold text-gray-800 mb-3">Students</h2>
        <div class="space-y-2 max-h-96 overflow-auto">
          <div v-for="student in students.data" :key="student.id" class="flex items-center justify-between border rounded p-2">
            <div>
              <div class="font-medium">{{ student.firstnames }} {{ student.surname }}</div>
              <div class="text-xs text-gray-500">{{ student.regnum }} • {{ student.department?.name }}</div>
            </div>
            <div class="flex gap-2">
              <button @click="mark(student.id)" class="px-2 py-1 bg-green-600 text-white rounded text-xs">Mark Attendance</button>
              <button @click="unmark(student.id)" class="px-2 py-1 bg-gray-300 text-gray-800 rounded text-xs">Unmark</button>
            </div>
          </div>
        </div>
        <div class="mt-3"><Pagination :links="students.links" /></div>
      </div>

      <!-- Invigilators -->
      <div class="bg-white rounded-xl shadow p-4">
        <h2 class="font-semibold text-gray-800 mb-3">Invigilators</h2>
        <div class="flex flex-wrap gap-2">
          <span v-for="inv in exam.invigilations" :key="inv.id" class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">{{ inv.user?.name }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Layout from '../../Shared/Layout.vue';
import Pagination from '../../Shared/Pagination.vue';
import { Link, router } from '@inertiajs/vue3';

export default {
  layout: Layout,
  components: { Pagination, Link },
  props: { exam: Object, students: Object },
  setup(props) {
    const mark = (studentId) => {
      router.post(`/lecturer/timetable/${props.exam.id}/attendance/${studentId}`, {}, { preserveScroll: true });
    };
    const unmark = (studentId) => {
      router.delete(`/lecturer/timetable/${props.exam.id}/attendance/${studentId}`, { preserveScroll: true });
    };
    return { mark, unmark };
  },
};
</script>

<style scoped>
</style>


