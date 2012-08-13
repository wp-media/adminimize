require "uglifier"       # gem install uglifier, requires node.js
require "yui/compressor" # gem install require "yui/compressor"

desc "prepare javascript files for production"
task "prepare:js" do
	Dir.glob("js/*.js").each do |file|
		next unless file.match /\.dev\./
		puts "> preparing #{file}"

		# minify
		minified = Uglifier.compile File.read(file)

		# write to production file
		new_file = "#{file.split(".")[0]}.js"
		File.open(new_file, 'w') { |f| f.write minified }
	end
end

desc "prepare css files for production"
task "prepare:css" do
	Dir.glob("css/*.css") do |file|
		next unless file.match /\.dev\./
		puts "> preparing #{file}"

		# minify
		compressor = YUI::CssCompressor.new
		minified   = compressor.compress File.read(file)

		# write to production file
		new_file = "#{file.split(".")[0]}.css"
		File.open(new_file, 'w') { |f| f.write minified }
	end
end

desc "prepare all assets for production"
task :prepare => ["prepare:js", "prepare:css"] do
	
end