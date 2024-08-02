
module.exports = function(grunt) {

    // Grunt config.
    grunt.initConfig({
      // Read package.json.
      pkg: grunt.file.readJSON('package.json'),
  
      // JSHint.
      jshint: {
        files: ['amd/src/**/*.js'], // Path to JS files.
        options: {
            jshintrc: '.jshintrc' // Use config file .jshintrc.
        }
      },
  
      // Uglify.
      uglify: {
        options: {
          banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
        },
        build: {
            files: [{
              expand: true,
              cwd: 'amd/src/', // Root repository.
              src: '**/*.js', // Target all JS files and sub-folders.
              dest: 'amd/build/', // Destination folders.
              ext: '.min.js' // Minified files extension.
            }]
        }
      },
  
      // Nodeunit (NOT USED YET).
      nodeunit: {
        all: ['test/**/*_test.js'] // Path to test files.
      },

    });

    // Load plugins.
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-nodeunit');
  
    // Tasks recording.
    // grunt.registerTask('default', ['jshint', 'uglify', 'nodeunit']);
    grunt.registerTask('default', ['uglify']);

  };
  