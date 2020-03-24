# Production server access
server "cld2-11.mycpnv.ch", user: "cld2_11"

set :deploy_to, "/home/cld2_11/cld2-11.mycpnv.ch"

set :keep_releases, 3
set :ssh_options, {
    keys: %w(home/cld2_11/.ssh/id_rsa),
    forward_agent: false,
    auth_methods: %w(publickey)
}

# Add package
SSHKit.config.command_map[:composer] = "php -d allow_url_fopen=true #{shared_path.join('composer')}"

# Ignore
Rake::Task['laravel:optimize'].clear_actions rescue nil
set :laravel_set_acl_paths, false
set :laravel_upload_dotenv_file_on_deploy, false

# Task
after 'composer:run', 'copy_dotenv'
task :copy_dotenv do
    on roles(:all) do
        execute :cp, "#{shared_path}/.env #{release_path}/.env"
    end
end

after 'copy_dotenv', 'laravel:migrate'