<template>
  <Head title="Lecturers" />
  <div class="p-6 bg-gray-100 min-h-screen space-y-6">
    <div class="mb-4">
      <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
        <i class="fas fa-chalkboard-teacher text-blue-600"></i> Lecturers
      </h1>
      <nav class="mt-1 text-sm text-gray-500 flex items-center gap-2">
        <a href="#" class="hover:text-blue-600 flex items-center gap-1">
          <i class="fas fa-home"></i> Home
        </a>
        <span>/</span>
        <span class="text-gray-800 font-medium flex items-center gap-1">
          Lecturers
        </span>
      </nav>
    </div>

    <!-- Header & Search -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
      <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2"></h1>

      <div class="flex items-center gap-3 w-full md:w-auto">
        <input
          v-model="search"
          type="text"
          placeholder="Search lecturers..."
          class="px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring focus:ring-blue-400 text-sm w-full md:w-64"
        />
        <Link
          href="/admin/lecturers/create"
          class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition flex items-center gap-2"
        >
          <i class="fas fa-plus"></i> Add Lecturer
        </Link>
      </div>
    </div>

    <!-- Lecturers Grid -->
    <div class="grid gap-4">
      <div
        v-for="lecturer in lecturers.data"
        :key="lecturer.id"
        class="flex flex-col md:flex-row justify-between items-center bg-white rounded-xl shadow p-4 hover:shadow-md transition"
      >
        <div class="flex items-center gap-4 w-full md:w-2/3">
          <div class="w-14 h-14 rounded-full border-2 border-blue-500 shadow bg-blue-100 flex items-center justify-center">
            <i class="fas fa-chalkboard-teacher text-blue-600 text-xl"></i>
          </div>
          <div>
            <p class="text-lg font-semibold text-gray-800">{{ lecturer.firstnames }} {{ lecturer.surname }}</p>
            <p class="text-sm text-gray-500">
              {{ lecturer.gender }} • {{ lecturer.department?.name }} • {{ lecturer.user?.email }}
            </p>
          </div>
        </div>

        <div class="flex items-center gap-2 mt-4 md:mt-0 w-full md:w-1/3 justify-end">
          <Link :href="`/admin/lecturers/${lecturer.id}`" class="text-blue-600 hover:text-blue-800 p-2">
            <i class="fas fa-eye"></i>
          </Link>
          <Link :href="`/admin/lecturers/${lecturer.id}/edit`" class="text-blue-600 hover:text-blue-800 p-2">
            <i class="fas fa-pen"></i>
          </Link>
          <button @click="openDeleteModal(lecturer.id)" class="text-red-600 hover:text-red-800 p-2">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </div>
    </div>

    <div v-if="lecturers.data.length === 0" class="text-center text-gray-500 py-12">
      <i class="fas fa-chalkboard-teacher text-4xl text-blue-200 mb-4"></i>
      <p class="text-lg font-medium">No lecturers found.</p>
      <p class="text-sm">Try adjusting your search or check later.</p>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center">
      <Pagination :links="lecturers.links" />
    </div>

    <!-- Delete Modal -->
    <div v-if="isDeleteModalOpen" class="fixed inset-0 backdrop-blur-sm bg-white/30 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Confirm Delete</h2>
        <p class="text-sm text-gray-600 mb-6">Are you sure you want to delete this lecturer?</p>
        <div class="flex justify-end gap-2">
          <button @click="closeDeleteModal" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
          <button @click="handleDelete(selectedLecturerId)" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
            Delete
          </button>
        </div>
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
  components: {
    Pagination,
    Link
  },
  props: {
    lecturers: Object,
    filters: Object
  },
  setup(props) {
    const search = ref(props.filters?.search || '');
    const isDeleteModalOpen = ref(false);
    const selectedLecturerId = ref(null);

    const openDeleteModal = (id) => {
      selectedLecturerId.value = id;
      isDeleteModalOpen.value = true;
    };

    const closeDeleteModal = () => {
      selectedLecturerId.value = null;
      isDeleteModalOpen.value = false;
    };

    const handleDelete = (id) => {
      router.delete(`/admin/lecturers/${id}`, {
        preserveState: true,
        onFinish: () => closeDeleteModal()
      });
    };

    watch(search, (value) => {
      router.get('/admin/lecturers', { search: value }, {
        preserveState: true,
        replace: true
      });
    });

    return {
      search,
      isDeleteModalOpen,
      selectedLecturerId,
      openDeleteModal,
      closeDeleteModal,
      handleDelete
    };
  }
};
</script>

<style scoped>
</style>


