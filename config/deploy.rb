# config valid only for Capistrano 3.1
lock '3.2.1'

set :application, 'WechatApiTest'
set :repo_url, 'https://github.com/benjah1/WechatApiTest.git'

# Default branch is :master
# ask :branch, proc { `git rev-parse --abbrev-ref HEAD`.chomp }.call

# Default deploy_to directory is /var/www/my_app
set :deploy_to, '/home/ubuntu/wechatApiTest'

set :use_sudo, true

# Default value for :scm is :git
# set :scm, :git

# Default value for :format is :pretty
# set :format, :pretty

# Default value for :log_level is :debug
# set :log_level, :debug

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []
# set :linked_files, %w{config/database.yml}

# Default value for linked_dirs is []
# set :linked_dirs, %w{bin log tmp/pids tmp/cache tmp/sockets vendor/bundle public/system}

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Default value for keep_releases is 5
# set :keep_releases, 5

namespace :deploy do

	desc 'Setup environment'
	task :setup do
		on roles(:all) do |host|
			info "Setup environment"
			execute("cd #{deploy_to}/current && sudo bash ./task/setup.sh")
		end
	end

	desc 'Turn off previous service'
	task :turnOff do
		on roles(:all) do |host|
			info "Turning off"
			execute("cd #{deploy_to}/current && sudo bash ./task/turnoff.sh")
		end
	end

	desc 'Build Image'
	task :build do
		on roles(:all) do |host|
			info "Build Image"
			execute("cd #{deploy_to}/current && sudo bash ./task/build.sh")
		end
	end

	desc 'Run Image'
	task :run do
		on roles(:all) do |host|
			info "Run Image"
			execute("cd #{deploy_to}/current && sudo bash ./task/run.sh")
		end
	end

	after :started, :setup
	after :updated, :turnOff
	after :finished, :build
	after :build, :run

end
