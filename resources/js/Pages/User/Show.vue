<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                User Show
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-5">
                    Name: {{ user.name }} <br>
                    Email: {{ user.email }} <br>
                    Role: {{ user.role }} <br>
                    Created_at: {{ user.created_at }} <br>
                    Updated_at: {{ user.updated_at }}
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
    components: {
        AppLayout
    },

    props: ['user'],
}
</script>