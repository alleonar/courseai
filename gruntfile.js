
module.exports = function(grunt) {

    // Grunt config.
    grunt.initConfig({
      // Read package.json.
      pkg: grunt.file.readJSON('package.json'),
  
      // JSHint.
      jshint: {
        files: ['amd/src/**/*.js'], // Chemin vers vos fichiers JS
        options: {
            jshintrc: '.jshintrc' // Utilise le fichier de configuration .jshintrc à la racine du projet
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
              cwd: 'amd/src/', // Répertoire source
              src: '**/*.js', // Pattern pour cibler tous les fichiers JS
              dest: 'amd/build/', // Répertoire de destination
              ext: '.min.js' // Extension des fichiers minifiés
            }]
        }
      },
  
      // Nodeunit (NOT USED YET).
      nodeunit: {
        all: ['test/**/*_test.js'] // Chemin vers vos fichiers de test
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
  