#Repository variables-----------------------------
set :scm,         :git
#set :deploy_via, :remote_cache
set :repository,  "https://yeaoh:yeaoh1@bitbucket.org/yeaoh/yeaoh.comen.git"
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, `subversion` or `none`


set  :keep_releases,  3
set :app_symlinks, ["/yeaoh/sites/default" , "/yeaoh/tmp"]
#set :app_shared_dirs, ["/yeaoh/sites/default"]
set :app_shared_files, ["/yeaoh/.htaccess"]
#set :app_root_dir, "#{current_path}/public"





#permission initiallization
namespace :deploy do
  desc <<-DESC
Cehckout the master branch
  DESC
  task :init, :roles => [:web, :app], :except => { :no_release => true } do
run"chown -Rf #{user} #{deploy_to}  && chmod 755 -Rf #{deploy_to}"
  end

  task :finalize_update, :roles => [:web, :app], :except => { :no_release => true } do
#do nothing
  end  
end


#task sequence
# Initialization Tasks sequence here--------------------------
after   'deploy:setup', 'deploy:init'
#after   'initialization', 'mage:setup'

# Deploy Tasks sequence here--------------------------
after   'deploy:update_code', 'drupal:settings_update'
#after   'mage:finalize_update', 'drupal:settings_update'

