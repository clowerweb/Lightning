var browserSync = require('browser-sync');

module.exports = function(grunt) {

    grunt.initConfig({

        pkg: grunt.file.readJSON('package.json'),

        dirs: {
            css:  "public/assets/css",
            scss: "src/assets/scss",
            js: "public/assets/js"
        },

        sass: {
            dist: {
                options: {
                    style: 'compressed'
                },
                files: {
                    '<%= dirs.css %>/main.css' : '<%= dirs.scss %>/main.scss'
                }
            }
        },

        uglify: {
            options: {
                sourceMap: true
            },
            build: {
                src: '<%= dirs.js %>/tmp/main.js',
                dest: '<%= dirs.js %>/main.js'
            }
        },

        autoprefixer: {
            dist: {
                options: {
                    browsers: ['> 1%'],
                    grid: true,
                    map: true
                },
                files: {
                    '<%= dirs.css %>/main.css' : '<%= dirs.css %>/main.css'
                }
            }
        },

        cssmin: {
            criticalcss: {
                files: [{
                    expand: true,
                    cwd: '<%= dirs.css %>/tmp',
                    src: ['critical.css'],
                    dest: '<%= dirs.css %>',
                    ext: '.css'
                }]
            },
        },

        concat: {
            dist: {
                src: [
                    'src/assets/js/main.js'
                ],
                dest: '<%= dirs.js %>/tmp/main.js'
            }
        },

        watch: {
            scss: {
                options: {
                    spawn: false,
                },
                files: ['<%= dirs.scss %>/**/*.scss'],
                tasks: ['sass', 'autoprefixer', 'bs-inject', 'notify:autoprefixer']
            },
            js: {
                options: {
                    spawn: false,
                },
                files: ['src/assets/js/**/*.js'],
                tasks: ['concat', 'uglify', 'bs-reload', 'notify:uglify']
            },
            html: {
                options: {
                    spawn: false,
                },
                files: ['App/Views/**/*.twig'],
                tasks: ['bs-reload']
            },
            php: {
                options: {
                    spawn: false,
                },
                files: ['App/Controllers/*.php', 'public/*.php'],
                tasks: ['bs-reload']
            }
        },

        notify: {
            autoprefixer: {
                options:{
                    title: "CSS Files built",
                    message: "SASS files rendered and auto-prefixed"
                }
            },
            uglify: {
                options:{
                    title: "JS Files built",
                    message: "JS files concatenated and minified"
                }
            }
        },

        notify_hooks: {
            options: {
                enabled: true,
                max_jshint_notifications: 5, // maximum number of notifications from jshint output
                success: true, // whether successful grunt executions should be notified automatically
                duration: 3 // the duration of notification in seconds, for `notify-send only
            }
        }

    });

    grunt.registerTask('bs-init', function () {
        browserSync({
            proxy: 'http://lightning.local',
        })
    });

    grunt.registerTask('bs-inject', function () {
        browserSync.reload('main.css');
    });

    grunt.registerTask('bs-reload', function () {
        browserSync.reload();
    });

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify-es');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-notify');

    grunt.task.run('notify_hooks');
    grunt.registerTask('default', ['bs-init', 'watch']);

};
