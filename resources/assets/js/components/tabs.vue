<template>
    <div>
        <div>
            <ul class="nav nav-tabs">
                <li v-for="tab in tabs" class="nav-item" :class="{ 'active': tab.isActive }">
                    <a :href="tab.href" @click="selectTab(tab)">{{ tab.name }}</a>
                </li>
            </ul>
        </div>

        <div>
            <slot />
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {tabs: [] };
        },
        created() {
            this.tabs = this.$children;
        },
        methods: {
            selectTab(selectedTab) {
                this.tabs.forEach(tab => {
                    tab.isActive = (tab.name === selectedTab.name);
                });
            }
        },
    }
</script>