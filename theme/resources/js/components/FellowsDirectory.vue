<template>
  <div class="fellows-directory max-w-lg lg:max-w-none my-20">
    <p>Type in the field to narrow your search:</p>

    <div class="lg:flex md:mb-12 lg:mb-20">
      <div class="mb-4 lg:pr-4 lg:w-1/4">
        <v-select 
          :options="nameOptions"
          :placeholder="'Name'"
          v-model="filters.name"
          @input="filtersChanged"
        ></v-select>
      </div>

      <div class="mb-4 lg:px-4 lg:w-1/3">
        <v-select 
          :options="schoolOptions"
          :placeholder="'School'"
          v-model="filters.school"
          @input="filtersChanged"
        ></v-select>
      </div>

      <div class="mb-4 lg:px-4 lg:w-1/3">
        <v-select 
          :options="studyOptions"
          :placeholder="'Field of Study'"
          v-model="filters.study"
          @input="filtersChanged"
        ></v-select>
      </div>

      <div class="mb-4 lg:pl-4 flex-none lg:w-48">
        <v-select 
          :options="yearOptions"
          :placeholder="'Year Awarded'"
          v-model="filters.year"
          @input="filtersChanged"
        ></v-select>
      </div>
    </div>

    <div class="hidden fellows-listing__header lg:flex">
      <div :class="headerClasses" class="pr-4 w-1/4">Name</div>
      <div :class="headerClasses" class="px-4 w-1/3">School</div>
      <div :class="headerClasses" class="px-4 w-1/3">Field of Study</div>
      <div :class="headerClasses" class="pl-4 flex-none w-48">Year</div>
    </div>

    <div class="fellows-listing my-4 lg:my-0 lg:flex" :class="{'bg-gray-400': index % 2 == 0}" v-for="(fellow, index) in fellows">
      <div class="fellow__name lg:pr-4 lg:w-1/4" :class="rowClasses">
        <span class="label lg:hidden">Name:</span>
        <a :href="fellow.link">{{ fellow.name }}</a>
      </div>
      <div class="fellow__school lg:w-1/3" :class="rowClasses">
        <span class="label lg:hidden">School:</span>
        <span class="value">{{ fellow.school }}</span>
      </div>
      <div class="fellow__study lg:px-4 lg:w-1/3" :class="rowClasses">
        <span class="label lg:hidden">Field of Study:</span>
        <span class="value">{{ fellow.study }}</span>
      </div>
      <div class="fellow__year lg:pl-4 flex-none lg:w-48" :class="rowClasses">
        <span class="label lg:hidden">Year:</span>
        <span class="value">{{ fellow.year }}</span>
      </div>
    </div>

    <div v-if="!fellows.length" class="no-results">Loading...</div>

    <div class="mt-16">
      <pager :pagination="pagination"></pager>
    </div>
    
  </div>
</template>



<script>
  export default {
    props: {
      pageSize: {
        type: Number,
        default: 20
      }
    },
    data() {
      return {
        fellows: [],
        pagination: [],
        schoolOptions: [],
        schoolSelected: '',
        yearOptions: [],
        yearSelected: '',
        studyOptions: [],
        studySelected: '',
        nameOptions: [],
        nameSelected: '',
        filters: {
          'name': '',
          'school': '',
          'study': '',
          'year': ''
        },
        headerClasses: 'font-header uppercase font-bold p-4',
        rowClasses: 'px-4 py-2 lg:py-4'
      }
    },
    methods: {
      filtersChanged() {
        // Update the url.
        let searchParams = new URLSearchParams(location.search);

        // Reset page number.
        searchParams.delete('pageNum');

        for (let i in this.filters) {
          let id = 'filter_' + i;

          if (this.filters[i]) {
            let value = this.filters[i].value;
            

            if (value) {
              searchParams.set(id, value);
            }
            else {
              searchParams.delete(id);
            }
          }
          else {
            searchParams.delete(id);
          }
        }

        location.search = searchParams.toString().length ? '?' + searchParams.toString() : '';
      },
      async getFellows() {

        try {
          
          const [filters, directory] = await Promise.all([
            axios.get('/wp-json/api/fellows/directory/filters'),
            axios.get('/wp-json/api/fellows/directory' + location.search)
          ]);

          let searchParams = new URLSearchParams(location.search);

          // Apply filters.
          this.nameOptions = filters.data.filters.names;
          if (searchParams.has('filter_name')) {
            this.filters.name = this.nameOptions.find(option => option.value == searchParams.get('filter_name'));
          }

          this.studyOptions = filters.data.filters.study_areas;
          if (searchParams.has('filter_study')) {
            this.filters.study = this.studyOptions.find(option => option.value === searchParams.get('filter_study'));
          }

          this.schoolOptions = filters.data.filters.schools;
          if (searchParams.has('filter_school')) {
            this.filters.school = this.schoolOptions.find(option => option.value === searchParams.get('filter_school'));
          }

          this.yearOptions = filters.data.filters.years;
          if (searchParams.has('filter_year')) {
            this.filters.year = this.yearOptions.find(option => option.value == searchParams.get('filter_year'));
          }

          // Apply Fellows
          this.fellows = directory.data.directory.fellows;
          this.pagination = directory.data.directory.pagination;

        }
        catch(err) {
          console.log(err);
        }
      },
      
    },
    mounted() {
      this.getFellows();
    }
  }
</script>
