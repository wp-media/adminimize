require "uglifier"       # gem install uglifier, requires node.js
require "yui/compressor" # gem install require "yui/compressor"

desc "prepare javascript files for production"
task "prepare:js" do
	minify "js/*.js" do |file|
		Uglifier.compile File.read(file)
	end
end

desc "prepare css files for production"
task "prepare:css" do
	minify "css/*.css" do |file|
		YUI::CssCompressor.new.compress File.read(file)
	end
end

desc "prepare all assets for production"
task :prepare => ["prepare:js", "prepare:css"] do
	
end

# Minify a set of files using the given method.
# 
# glob  - filenames found by expanding the pattern, e.g. *.js
#         automatically skips all files without ".dev."
# block - takes file name and is expected to return minified file contents
def minify(glob, &block)
	Dir.glob(glob) do |file|
		next unless file.match /\.dev\./
		puts "> preparing #{file}"

		# minify
		minified = block.call file

		# write to production file
		file_parts = file.split(".")
		new_file  = "#{file_parts[0]}.#{file_parts[2]}"
		File.open(new_file, 'w') { |f| f.write minified }
	end	
end