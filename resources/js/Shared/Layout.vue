<template>
    <div class="flex h-screen overflow-hidden bg-gray-100 text-gray-800">
        <Sidebar :isCollapsed="isCollapsed" :role="role" @toggle="toggleSidebar" />

        <div class="flex-1 overflow-y-auto">
            <Header :username="username" :role="role" @toggle="toggleSidebar" />

            <main class="p-6 space-y-6 overflow-y-auto">
                <slot />
            </main>
        </div>
    </div>
</template>

<script>
import Sidebar from './Sidebar.vue';
import Header from './Header.vue';

export default {
    components: { Sidebar, Header },
    data() {
        return {
            isCollapsed: false
        };
    },
    methods: {
        toggleSidebar() {
            this.isCollapsed = !this.isCollapsed;
        }
    },
    computed: {
        username() {
            return this.$page.props.auth.user.name
        },
        role() {
            return this.$page.props.auth.role.name
        }
    }
};
</script>
