<script>

</script>

<template>
    <login-layout>
        <section class="bg-gray-50 dark:bg-gray-900">
            <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
                <a href="#" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                    <img class="w-full h-20 mr-2" :src="'/storage/images/logo/logo.png'" alt="logo">
                        
                </a>
                <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
                    <Main>
                        <slot />
                    </Main>
                </div>
            </div>
        </section>
    </login-layout>
</template>

