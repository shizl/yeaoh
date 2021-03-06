#specific task
_cset(:app_shared_files)  {
  abort "Please specify an array of shared files to be symlinked, set :app_shared_files"
}

_cset(:app_symlinks) {
  abort "Please specify an array of symlinks to shared resources, set :app_symlinks, ['/media', ./. '/staging']"
}
namespace :drupal do
desc <<-DESC
    Touches up the released code. This is called by update_code \
    after the basic deploy finishes.
    Any directories deployed from the SCM are first removed and then replaced with \
    symlinks to the same directories within the shared location.
  DESC
  task :settings_update, :roles => [:web, :app], :except => { :no_release => true } do    
    run "chmod -R g+w #{latest_release}" if fetch(:group_writable, true)
    
     if app_symlinks
      # Remove the contents of the shared directories if they were deployed from SCM
      app_symlinks.each { |link| run "#{try_sudo} rm -rf #{latest_release}#{link}" }
      # Add symlinks the directoris in the shared location
      app_symlinks.each { |link| run "ln -nfs #{shared_path}#{link} #{latest_release}#{link}" }
    end   
    if app_shared_files
      # Remove the contents of the shared directories if they were deployed from SCM
      app_shared_files.each { |link| run "#{try_sudo} rm -rf #{latest_release}#{link}" }
      # Add symlinks the directoris in the shared location
      app_shared_files.each { |link| run "ln -s #{shared_path}#{link} #{latest_release}#{link}" }
    end
  end
end